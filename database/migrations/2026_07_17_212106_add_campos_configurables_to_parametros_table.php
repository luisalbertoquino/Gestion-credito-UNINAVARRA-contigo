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
        Schema::table('parametros', function (Blueprint $table) {
            $table->string('nombre_institucion')->default('UNINAVARRA Contigo');
            $table->string('texto_verde')->default('APROBADO');
            $table->string('subtexto_verde')->default('Cumple los criterios del estudio de crédito');
            $table->string('texto_amarillo')->default('REQUIERE REVISIÓN');
            $table->string('subtexto_amarillo')->default('Aprobable con garantía o análisis adicional');
            $table->string('texto_rojo')->default('NO VIABLE');
            $table->string('subtexto_rojo')->default('No cumple la capacidad de pago requerida');
            $table->json('campos_visibles')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parametros', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_institucion', 'texto_verde', 'subtexto_verde',
                'texto_amarillo', 'subtexto_amarillo', 'texto_rojo', 'subtexto_rojo',
                'campos_visibles',
            ]);
        });
    }
};
