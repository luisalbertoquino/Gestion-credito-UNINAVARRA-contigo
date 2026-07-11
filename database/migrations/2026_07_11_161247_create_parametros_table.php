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
        Schema::create('parametros', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('umbral_verde')->default(30);
            $table->unsignedTinyInteger('umbral_amarillo')->default(40);
            $table->decimal('min_cobertura', 5, 2)->default(2);
            $table->unsignedTinyInteger('cuota_inicial_minima_pct')->default(50);
            $table->unsignedTinyInteger('min_cuotas')->default(1);
            $table->unsignedTinyInteger('max_cuotas')->default(6);
            $table->decimal('tasa_mensual', 5, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros');
    }
};
