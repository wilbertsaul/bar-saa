<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('tipo', ['entrada', 'salida', 'ajuste'])->default('entrada');
            $table->integer('cantidad');
            $table->integer('stock_antes');
            $table->integer('stock_despues');
            $table->string('motivo')->nullable()->comment('Venta, ingreso de caja, ajuste manual, etc.');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
