<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\RealisateurController;
use App\Http\Controllers\ActeurController;
use App\Http\Controllers\ProjectionController;

// --- Routes publiques ---
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/films', [PublicController::class, 'films'])->name('public.films');
Route::get('/realisateurs-acteurs', [PublicController::class, 'realisateursActeurs'])->name('public.realisateurs_acteurs');
Route::get('/projections', [PublicController::class, 'projections'])->name('public.projections');
Route::get('/projections/{projection}/visionner', [PublicController::class, 'visionner'])->name('public.projections.visionner');
Route::get('/actualites', [PublicController::class, 'actualites'])->name('public.actualites');
Route::get('/galerie', [PublicController::class, 'galerie'])->name('public.galerie');
Route::get('/a-propos', [PublicController::class, 'aPropos'])->name('public.a_propos');
Route::get('/contact', [PublicController::class, 'contact'])->name('public.contact');

// --- Routes admin (auth + admin) ---
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/actualites', [AdminController::class, 'actualites'])->name('actualites');
    Route::get('/galerie', [AdminController::class, 'galerie'])->name('galerie');

    Route::resource('films', FilmController::class);
    Route::resource('realisateurs', RealisateurController::class);
    Route::resource('acteurs', ActeurController::class);
    Route::resource('projections', ProjectionController::class);
    Route::post('projections/{projection}/demarrer', [ProjectionController::class, 'demarrer'])->name('projections.demarrer');
    Route::post('projections/{projection}/arreter',  [ProjectionController::class, 'arreter'])->name('projections.arreter');
});


// --- Routes profil utilisateur ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Auth routes ---
require __DIR__.'/auth.php';