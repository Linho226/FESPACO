<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('description')->nullable();
            $table->year('annee_production');
            $table->string('pays');
            $table->integer('duree'); // en minutes
            $table->string('affiche')->nullable(); // chemin ou url de l'affiche
            $table->string('realisateur');
            $table->string('acteurs'); // liste sous forme de texte, à améliorer plus tard
            $table->string('categorie');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};