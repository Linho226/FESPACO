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
}