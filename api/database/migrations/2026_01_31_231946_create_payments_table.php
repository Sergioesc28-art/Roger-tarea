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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            
            $table->decimal('amount', 10, 2);
            $table->string('concept'); // 'Inscripción Sept-Dic'
            $table->date('due_date');  // Fecha límite
            $table->dateTime('paid_at')->nullable(); // Fecha real de pago
            
            $table->enum('status', ['Pendiente', 'Pagado', 'Vencido', 'Cancelado'])->default('Pendiente');
            $table->string('payment_method')->nullable(); // Transferencia, Efectivo
            $table->string('transaction_reference')->nullable(); // Folio del banco
            
            $table->timestamps();
            
            // Índice para buscar pagos pendientes rápidamente
            $table->index(['student_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
