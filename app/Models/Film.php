<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'video_links',
        'video_files',
    ];
}