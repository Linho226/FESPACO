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
        Schema::table('films', function (Blueprint $table) {
            $table->text('video_links')->nullable(); // pour stocker les liens multiples (JSON ou texte)
            $table->text('video_files')->nullable(); // pour stocker les chemins des fichiers multiples (JSON)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('films', function (Blueprint $table) {
            $table->dropColumn('video_links');
            $table->dropColumn('video_files');
        });
    }
};