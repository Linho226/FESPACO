<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projections', function (Blueprint $table) {
            // Mode de sélection: 'all' (tous les médias en séquence) ou 'specific' (médias sélectionnés)
            $table->enum('media_selection_mode', ['all', 'specific'])->default('all')->after('media_id');
            
            // Liste des IDs de galerie sélectionnés (en JSON) pour le mode 'specific'
            $table->json('selected_media_ids')->nullable()->after('media_selection_mode');
        });
    }

    public function down(): void
    {
        Schema::table('projections', function (Blueprint $table) {
            $table->dropColumn(['media_selection_mode', 'selected_media_ids']);
        });
    }
};
