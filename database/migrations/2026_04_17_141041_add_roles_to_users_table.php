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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin')->after('email'); // admin, socio, empresa
            $table->foreignId('socio_id')->nullable()->after('role')->constrained('socios')->onDelete('cascade');
            $table->foreignId('empresa_id')->nullable()->after('socio_id')->constrained('empresas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            $table->dropForeign(['socio_id']);
            $table->dropColumn(['role', 'socio_id', 'empresa_id']);
        });
    }
};
