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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            // Relación 1:1 estricta con Users
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('career_id')->constrained('careers');
            
            $table->string('enrollment_number')->unique(); // Matrícula
            $table->string('curp', 18)->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->text('address')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->tinyInteger('current_quarter')->default(1);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
