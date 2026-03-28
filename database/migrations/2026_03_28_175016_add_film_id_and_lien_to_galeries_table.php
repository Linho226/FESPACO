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
        Schema::table('galeries', function (Blueprint $table) {
            $table->foreignId('film_id')->nullable()->constrained('films')->nullOnDelete()->after('id');
            $table->string('lien')->nullable()->after('fichier');
            $table->string('fichier')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeries', function (Blueprint $table) {
            $table->dropForeign(['film_id']);
            $table->dropColumn(['film_id', 'lien']);
            $table->string('fichier')->nullable(false)->change();
        });
    }
};
