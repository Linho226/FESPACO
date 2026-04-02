<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;

class Projection extends Model
{
    public const ACTIVE_VIEWER_TTL_SECONDS = 30;

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
        'media_id',
        'media_selection_mode',
        'selected_media_ids',
    ];

    protected $casts = [
        'date'     => 'date',
        'publie'   => 'boolean',
        'debut_at' => 'datetime',
        'fin_at'   => 'datetime',
        'pause_total_seconds' => 'integer',
        'selected_media_ids' => 'array',
    ];

    public function film(): BelongsTo
    {
        return $this->belongsTo(Film::class);
    }

    public function attendanceRecord(): HasOne
    {
        return $this->hasOne(AttendanceRecord::class);
    }

    public function registerActiveViewer(int $userId, ?Carbon $seenAt = null): void
    {
        $seenAt ??= now();
        $viewers = $this->pruneActiveViewers($seenAt);
        $viewers[(string) $userId] = $seenAt->timestamp;

        Cache::put(
            $this->activeViewersCacheKey(),
            $viewers,
            $seenAt->copy()->addSeconds(self::ACTIVE_VIEWER_TTL_SECONDS * 2)
        );
    }

    public function activeViewersCount(?Carbon $referenceTime = null): int
    {
        return count($this->pruneActiveViewers($referenceTime));
    }

    public function activeViewersCacheKey(): string
    {
        return 'projection:' . $this->id . ':active_viewers';
    }

    private function pruneActiveViewers(?Carbon $referenceTime = null): array
    {
        $referenceTime ??= now();
        $rawViewers = Cache::get($this->activeViewersCacheKey(), []);

        if (!is_array($rawViewers)) {
            Cache::forget($this->activeViewersCacheKey());
            return [];
        }

        $cutoff = $referenceTime->copy()->subSeconds(self::ACTIVE_VIEWER_TTL_SECONDS)->timestamp;

        $activeViewers = collect($rawViewers)
            ->filter(fn ($timestamp) => is_numeric($timestamp) && (int) $timestamp >= $cutoff)
            ->map(fn ($timestamp) => (int) $timestamp)
            ->all();

        if ($activeViewers === []) {
            Cache::forget($this->activeViewersCacheKey());
            return [];
        }

        Cache::put(
            $this->activeViewersCacheKey(),
            $activeViewers,
            $referenceTime->copy()->addSeconds(self::ACTIVE_VIEWER_TTL_SECONDS * 2)
        );

        return $activeViewers;
    }

    /** Date+heure planifiées en objet Carbon. */
    public function dateHeure(): Carbon
    {
        return Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->heure);
    }

    /** Fin théorique selon l'heure de début et la durée du film. */
    public function finPrevue(): Carbon
    {
        return $this->dateHeure()->copy()->addSeconds($this->dureeProjectionSecondes());
    }

    public function dureeProjectionSecondes(): int
    {
        // Si pas de sélection de médias, utiliser la durée du film entier
        if (!$this->media_selection_mode || $this->media_selection_mode === 'all') {
            return $this->film->dureeSecondesReelle();
        }

        // Mode 'specific': calculer la durée totale des médias sélectionnés
        if (empty($this->selected_media_ids)) {
            return 0;
        }

        $totalSeconds = 0;
        
        // Essayer de charger les galeries depuis la relation du film (optimisé en mémoire)
        $galeries = $this->film?->galeries;
        
        if ($galeries) {
            // Galeries sont déjà chargées
            $galerieMap = $galeries->keyBy('id');
            foreach ($this->selected_media_ids as $mediaId) {
                if (isset($galerieMap[$mediaId]) && $galerieMap[$mediaId]->duree_secondes) {
                    $totalSeconds += $galerieMap[$mediaId]->duree_secondes;
                }
            }
        } else {
            // Fallback: chercher dans la base de données
            foreach ($this->selected_media_ids as $mediaId) {
                $galerie = Galerie::find($mediaId);
                if ($galerie && $galerie->duree_secondes) {
                    $totalSeconds += $galerie->duree_secondes;
                }
            }
        }

        return $totalSeconds;
    }

    /**
     * Récupère le titre à afficher pour la projection.
     * Si mode 'specific' avec sélection, combine les titres des médias.
     * Sinon, retourne le titre du film.
     */
    public function getTitreAffiche(): string
    {
        // Si pas de sélection spécifique, afficher le film
        if (!$this->media_selection_mode || $this->media_selection_mode === 'all' || empty($this->selected_media_ids)) {
            return $this->film->titre ?? 'Sans titre';
        }

        // Mode 'specific': afficher les titres des médias sélectionnés
        $titres = [];
        
        // Essayer de charger les galeries depuis la relation du film (optimisé en mémoire)
        $galeries = $this->film?->galeries;
        
        if ($galeries) {
            // Galeries sont déjà chargées
            $galerieMap = $galeries->keyBy('id');
            foreach ($this->selected_media_ids as $mediaId) {
                if (isset($galerieMap[$mediaId])) {
                    $titres[] = $galerieMap[$mediaId]->titre;
                }
            }
        } else {
            // Fallback: chercher dans la base de données
            foreach ($this->selected_media_ids as $mediaId) {
                $galerie = Galerie::find($mediaId);
                if ($galerie) {
                    $titres[] = $galerie->titre;
                }
            }
        }

        return count($titres) > 0 ? implode(' + ', $titres) : ($this->film->titre ?? 'Sans titre');
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
