<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('categoria', ['ron', 'whisky', 'cerveza', 'sin_alcohol'])->default('cerveza');
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(10)->comment('Alerta de stock bajo');
            $table->decimal('precio_venta', 8, 2)->comment('Precio carta: efectivo, yape y plin');
            $table->decimal('precio_tarjeta', 8, 2)->comment('Precio con tarjeta de crédito/débito');
            $table->decimal('precio_costo', 8, 2)->nullable();
            $table->integer('unidades_por_caja')->default(1)->comment('Para conversión caja→unidades al ingresar stock');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
