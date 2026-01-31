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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('course_id')->constrained('courses');
            
            // Calificaciones (pueden ser nulas al principio)
            $table->decimal('grade_p1', 4, 2)->nullable(); // Parcial 1
            $table->decimal('grade_p2', 4, 2)->nullable(); // Parcial 2
            $table->decimal('final_grade', 4, 2)->nullable();
            
            $table->timestamps();
            
            // Regla de Negocio: Un alumno no puede inscribir la misma clase dos veces
            $table->unique(['student_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
