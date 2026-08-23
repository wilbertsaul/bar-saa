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
        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->boolean('es_merma')->default(false)->after('tipo');
            $table->foreignId('caja_id')->nullable()->constrained('cajas')->nullOnDelete()->after('es_merma');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimientos_inventario', function (Blueprint $table) {
            $table->dropForeign(['caja_id']);
            $table->dropColumn(['es_merma', 'caja_id']);
        });
    }
};
