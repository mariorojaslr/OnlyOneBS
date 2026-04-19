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
        Schema::create('viajes', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->foreignId('pasajero_id')->constrained('pasajeros');
            $table->foreignId('centro_costo_id')->constrained('centro_costos');
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->text('origen')->nullable();
            $table->text('destino')->nullable();
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->decimal('monto', 15, 2)->default(0);
            $table->string('distancia')->nullable();
            $table->string('estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};
