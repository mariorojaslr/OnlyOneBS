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
        Schema::table('viajes', function (Blueprint $table) {
            $table->unsignedBigInteger('cierre_facturacion_id')->nullable()->after('estado');
            $table->foreign('cierre_facturacion_id')->references('id')->on('cierre_facturacions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropForeign(['cierre_facturacion_id']);
            $table->dropColumn('cierre_facturacion_id');
        });
    }
};
