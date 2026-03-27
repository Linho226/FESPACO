<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Realisateur;
use App\Models\Acteur;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home');
    }

    public function films()
    {
        $films = Film::orderBy('created_at', 'desc')->paginate(12); // récupère tous les films
        return view('public.films', compact('films')); // envoie à la vue
    }

    public function realisateursActeurs()
    {
        $realisateurs = Realisateur::all();
        $acteurs = Acteur::all();
        return view('public.realisateurs-acteurs', compact('realisateurs', 'acteurs'));
    }

    public function projections()
    {
        return view('public.projections');
    }

    public function actualites()
    {
        return view('public.actualites');
    }

    public function galerie()
    {
        return view('public.galerie');
    }

    public function aPropos()
    {
        return view('public.a-propos');
    }

    public function contact()
    {
        return view('public.contact');
    }
}