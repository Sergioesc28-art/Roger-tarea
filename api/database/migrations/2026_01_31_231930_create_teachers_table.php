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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            // Relación 1:1 estricta con Users
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            
            $table->string('first_name');
            $table->string('last_name');
            $table->string('rfc', 13)->unique()->nullable();
            $table->string('professional_license')->nullable();
            $table->string('phone_number')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
