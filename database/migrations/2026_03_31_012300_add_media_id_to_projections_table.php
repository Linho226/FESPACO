<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projections', function (Blueprint $table) {
            $table->foreignId('media_id')->nullable()->constrained('galeries')->nullOnDelete()->after('film_id');
        });
    }

    public function down(): void
    {
        Schema::table('projections', function (Blueprint $table) {
            $table->dropForeign(['media_id']);
            $table->dropColumn('media_id');
        });
    }
};