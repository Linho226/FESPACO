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
        Schema::table('projections', function (Blueprint $table) {
            $table->timestamp('debut_at')->nullable()->after('publie');
            $table->timestamp('fin_at')->nullable()->after('debut_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projections', function (Blueprint $table) {
            $table->dropColumn(['debut_at', 'fin_at']);
        });
    }
};
