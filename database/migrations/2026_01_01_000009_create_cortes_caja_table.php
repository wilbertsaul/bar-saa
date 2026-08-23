<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cortes_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->comment('Admin que realizó el corte');
            $table->date('fecha');
            $table->json('caja_ids')->nullable()->comment('IDs de cajas incluidas en el corte');
            $table->decimal('total_ventas', 12, 2)->default(0);
            $table->decimal('total_neto', 12, 2)->default(0);
            $table->integer('diferencia_inventario')->default(0)->comment('Unidades: consumo registrado vs. stock descontado');
            $table->text('observaciones')->nullable();
            $table->timestamp('cerrado_en')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cortes_caja');
    }
};
