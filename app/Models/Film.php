<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Film extends Model
{
    //
    protected $fillable = [
        'titre',
        'description',
        'annee_production',
        'pays',
        'duree',
        'duree_secondes',
        'affiche',
        'realisateur',
        'acteurs',
        'categorie',
        'type',
        'video',
        'video_link',
        'video_links',
        'video_files',
    ];

    public function projections(): HasMany
    {
        return $this->hasMany(Projection::class);
    }

    public function galeries(): HasMany
    {
        return $this->hasMany(Galerie::class);
    }

    /**
     * Durée exacte en secondes depuis le média vidéo associé.
     * Fallback sur duree_secondes du film, puis sur duree (minutes) × 60.
     */
    public function dureeSecondesReelle(): int
    {
        $media = $this->galeries()
            ->where('type_media', 'video')
            ->whereNotNull('duree_secondes')
            ->orderByDesc('created_at')
            ->first();

        if ($media && $media->duree_secondes > 0) {
            return $media->duree_secondes;
        }

        if ($this->duree_secondes > 0) {
            return $this->duree_secondes;
        }

        return max(1, (int) $this->duree) * 60;
    }

    public function dureeFormatee(): string
    {
        return self::formatterDureeSecondes($this->dureeSecondesReelle());
    }

    public static function formatterDureeSecondes(int $secondes): string
    {
        if ($secondes <= 0) {
            return 'Non renseignee';
        }

        $heures = intdiv($secondes, 3600);
        $minutes = intdiv($secondes % 3600, 60);
        $resteSecondes = $secondes % 60;

        if ($heures > 0) {
            if ($resteSecondes > 0) {
                return $heures . 'h' . $minutes . 'min' . $resteSecondes . 's';
            }

            return $heures . 'h' . $minutes;
        }

        if ($resteSecondes > 0) {
            return $minutes . 'min' . $resteSecondes . 's';
        }

        return $minutes . 'min';
    }
}