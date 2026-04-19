<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('socios', function (Blueprint $table) {
            if (!Schema::hasColumn('socios', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('id')->constrained('socios')->onDelete('set null');
            }
            if (!Schema::hasColumn('socios', 'nivel')) {
                $table->integer('nivel')->default(1)->after('parent_id'); // 1: White Label Owner, 2: Provincia/Sucursal
            }
        });
    }

    public function down(): void
    {
        Schema::table('socios', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'nivel']);
        });
    }
};
