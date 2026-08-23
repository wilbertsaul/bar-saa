<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('Usuario que registró la venta');
            $table->foreignId('caja_id')->nullable()->constrained('cajas')->nullOnDelete();
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('tipo_pago', ['efectivo', 'yape', 'plin', 'tarjeta_credito', 'tarjeta_debito'])->default('efectivo');
            $table->timestamp('fecha_hora');
            $table->enum('estado', ['activa', 'anulada'])->default('activa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
