<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galerie extends Model
{
    use HasFactory;

    protected $casts = [
        'date' => 'date',
    ];

    protected $fillable = [
        'film_id',
        'titre',
        'type_media',
        'fichier',
        'lien',
        'duree_secondes',
        'description',
        'date',
    ];

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    /**
     * Retourne l'URL d'embed iframe pour YouTube ou Vimeo,
     * null si le lien n'est pas un fournisseur supporté.
     */
    public function embedUrl(): ?string
    {
        if (!$this->lien) {
            return null;
        }

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_\-]{11})/', $this->lien, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1] . '?rel=0';
        }

        if (preg_match('/youtube\.com\/shorts\/([^?&]+)/', $this->lien, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1] . '?rel=0';
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $this->lien, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        return null;
    }
}