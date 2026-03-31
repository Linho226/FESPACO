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
}