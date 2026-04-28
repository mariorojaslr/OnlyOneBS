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
        Schema::table('empresas', function (Blueprint $table) {
            $table->enum('ciclo_facturacion', ['semanal', 'quincenal', 'mensual'])->default('mensual')->after('cuenta_corriente');
            $table->string('moneda', 3)->default('ARS')->after('ciclo_facturacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['ciclo_facturacion', 'moneda']);
        });
    }
};
