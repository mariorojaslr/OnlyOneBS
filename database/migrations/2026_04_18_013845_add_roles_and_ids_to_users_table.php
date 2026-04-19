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
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('empresa')->after('email');
            }
            if (!Schema::hasColumn('users', 'socio_id')) {
                $table->foreignId('socio_id')->nullable()->after('role')->constrained('socios')->onDelete('cascade');
            }
            if (!Schema::hasColumn('users', 'empresa_id')) {
                $table->foreignId('empresa_id')->nullable()->after('socio_id')->constrained('empresas')->onDelete('cascade');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'telegram_id')) {
                $table->string('telegram_id')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'use_2fa')) {
                $table->boolean('use_2fa')->default(false)->after('telegram_id');
            }
            if (!Schema::hasColumn('users', 'locale')) {
                $table->string('locale', 5)->default('es')->after('use_2fa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'socio_id', 'empresa_id', 'phone', 'telegram_id', 'use_2fa', 'locale']);
        });
    }
};
