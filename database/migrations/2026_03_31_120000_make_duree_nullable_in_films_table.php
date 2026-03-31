<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('films', function (Blueprint $table) {
            $table->integer('duree')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('films', function (Blueprint $table) {
            $table->integer('duree')->nullable(false)->default(0)->change();
        });
    }
};
