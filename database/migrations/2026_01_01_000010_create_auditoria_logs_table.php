<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('modelo')->comment('Nombre de la clase Eloquent afectada');
            $table->unsignedBigInteger('modelo_id');
            $table->string('accion')->comment('crear, actualizar, eliminar, anular');
            $table->json('data_anterior')->nullable();
            $table->json('data_nueva')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('realizado_en');
            $table->index(['modelo', 'modelo_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_logs');
    }
};
