<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Projection extends Model
{
    protected $fillable = [
        'film_id',
        'date',
        'heure',
        'salle',
        'lieu',
        'notes',
        'publie',
        'debut_at',
        'fin_at',
        'pause_total_seconds',
    ];

    protected $casts = [
        'date'     => 'date',
        'publie'   => 'boolean',
        'debut_at' => 'datetime',
        'fin_at'   => 'datetime',
        'pause_total_seconds' => 'integer',
    ];

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    /** Date+heure planifiées en objet Carbon. */
    public function dateHeure(): Carbon
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->heure);
    }

    /** Fin théorique selon l'heure de début et la durée du film. */
    public function finPrevue(): Carbon
    {
        $duree = (int) ($this->film->duree ?? 0);
        if ($duree <= 0) {
            $duree = 180;
        }

        return $this->dateHeure()->copy()->addMinutes($duree);
    }

    public function dureeProjectionSecondes(): int
    {
        $duree = (int) ($this->film->duree ?? 0);
        if ($duree <= 0) {
            $duree = 180;
        }

        return $duree * 60;
    }

    public function referenceDebutReel(): ?Carbon
    {
        if ($this->debut_at !== null) {
            return $this->debut_at->copy();
        }

        if (now()->gte($this->dateHeure())) {
            return $this->dateHeure();
        }

        return null;
    }

    public function tempsEcouleSecondes(): int
    {
        $debut = $this->referenceDebutReel();
        if ($debut === null) {
            return 0;
        }

        $borne = $this->fin_at ? $this->fin_at->copy() : now();
        $brut = max(0, $debut->diffInSeconds($borne, false));

        return max(0, $brut - (int) $this->pause_total_seconds);
    }

    /** Arrêtée manuellement par l'admin. */
    public function estArreteeManuellement(): bool
    {
        return $this->fin_at !== null && !$this->estTerminee();
    }

    /** En cours (auto selon horaire, ou manuellement démarrée). */
    public function estEnCours(): bool
    {
        if ($this->estTerminee()) {
            return false;
        }

        if ($this->fin_at !== null) {
            return false;
        }

        if ($this->debut_at !== null) {
            return true;
        }

        $debutPlanifie = $this->dateHeure();
        $finPlanifiee = $this->finPrevue();

        return now()->between($debutPlanifie, $finPlanifiee);
    }

    /** Pas encore commencée (et pas arrêtée manuellement). */
    public function estAVenir(): bool
    {
        return !$this->estTerminee()
            && $this->fin_at === null
            && $this->debut_at === null
            && now()->lt($this->dateHeure());
    }

    /** Terminée automatiquement selon l'horaire prévu. */
    public function estTerminee(): bool
    {
        return $this->tempsEcouleSecondes() >= $this->dureeProjectionSecondes();
    }

    /** Approche dans moins de 24h. */
    public function approcheImminente(): bool
    {
        if (!$this->estAVenir()) {
            return false;
        }

        $debut = $this->dateHeure();
        return now()->lt($debut) && now()->diffInMinutes($debut) <= 1440;
    }

    /** Libellé, couleur et icône de l'état. */
    public function etat(): array
    {
        if ($this->estArreteeManuellement()) {
            return ['label' => 'Arrêtée', 'badge' => 'danger', 'icon' => '⏹️'];
        }

        if ($this->estEnCours()) {
            return ['label' => 'En cours', 'badge' => 'success', 'icon' => '🟢'];
        }

        if ($this->estTerminee()) {
            return ['label' => 'Terminée', 'badge' => 'secondary', 'icon' => '⚫'];
        }

        return ['label' => 'À venir', 'badge' => 'primary', 'icon' => '🔵'];
    }
}
