<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('Operador que abre');
            $table->date('fecha_trabajo')->comment('Fecha de trabajo (fecha apertura)');
            $table->time('hora_apertura');
            $table->decimal('monto_inicial', 10, 2)->default(0);
            $table->decimal('ventas', 12, 2)->default(0)->comment('Ventas del turno');
            $table->decimal('gastos', 10, 2)->default(0)->comment('Gastos del turno');
            $table->decimal('mermas', 10, 2)->default(0)->comment('Mermas del turno');
            $table->time('hora_cierre')->nullable();
            $table->decimal('monto_arqueo', 10, 2)->nullable()->comment('Efectivo al cerrar');
            $table->decimal('diferencia', 10, 2)->default(0)->comment('Diferencia de caja');
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->foreignId('cerrada_por_id')->nullable()->constrained('users')->comment('Operador que cierra');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
