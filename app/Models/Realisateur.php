<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Realisateur extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'nationalite', 
        'biographie',
        'photo',
        'type',      
    ];
}