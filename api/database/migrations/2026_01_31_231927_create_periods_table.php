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
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // '2026-A'
            $table->date('start_date');
            $table->date('end_date');
            // El status controla si se pueden abrir clases o subir calificaciones
            $table->enum('status', ['Planeacion', 'Activo', 'Cerrado'])->default('Planeacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};
