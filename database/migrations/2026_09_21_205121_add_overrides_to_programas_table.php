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
        Schema::table('programas', function (Blueprint $table) {
            $table->unsignedTinyInteger('cuota_inicial_pct')->nullable()->after('matricula');
            $table->decimal('tasa_mensual', 5, 2)->nullable()->after('cuota_inicial_pct');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programas', function (Blueprint $table) {
            $table->dropColumn(['cuota_inicial_pct', 'tasa_mensual']);
        });
    }
};
