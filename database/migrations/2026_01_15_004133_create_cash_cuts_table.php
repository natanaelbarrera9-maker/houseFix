<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_cuts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Quién hizo el corte
            $table->decimal('sales_amount', 10, 2);      // Venta según el sistema
            $table->decimal('expenses_amount', 10, 2)->default(0); // Gastos/Pagos registrados
            $table->decimal('expected_amount', 10, 2);   // Cuánto debería haber (Venta - Gastos)
            $table->decimal('counted_amount', 10, 2);    // Cuánto contó el usuario
            $table->decimal('difference', 10, 2);        // Sobrante o Faltante
            $table->text('notes')->nullable();           // Notas del corte
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_cuts');
    }
};