<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acteur extends Model
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