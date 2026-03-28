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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projection_id')->constrained()->onDelete('cascade');
            $table->integer('spectators_count')->default(0)->comment('Nombre de spectateurs');
            $table->integer('available_seats')->default(0)->comment('Nombre de places disponibles');
            $table->decimal('occupancy_rate', 5, 2)->default(0)->comment('Taux d\'occupation en %');
            $table->timestamps();

            $table->index('projection_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
