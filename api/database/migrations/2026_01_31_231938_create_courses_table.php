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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('periods');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('teacher_id')->constrained('teachers');
            $table->foreignId('classroom_id')->constrained('classrooms');
            
            $table->string('group_name', 5); // 'A', 'B'
            
            // Horarios
            $table->enum('day_of_week', ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado']);
            $table->time('start_time');
            $table->time('end_time');
            
            // Control de Cupo
            $table->integer('max_quota');
            $table->integer('current_quota')->default(0);
            
            $table->timestamps();
            $table->softDeletes();

            // Índices Compuestos para optimizar la validación de choques de horario
            $table->index(['teacher_id', 'day_of_week', 'start_time'], 'idx_teacher_schedule');
            $table->index(['classroom_id', 'day_of_week', 'start_time'], 'idx_classroom_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
