<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function films()
    {
        return view('admin.films.index');
    }

    public function acteurs()
    {
        return view('admin.acteurs');
    }

    public function projections()
    {
        return view('admin.projections');
    }

    public function actualites()
    {
        return view('admin.actualites');
    }

    public function galerie()
    {
        return view('admin.galerie');
    }
}