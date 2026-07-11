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
        Schema::create('estudios', function (Blueprint $table) {
            $table->id();

            // Deudor (estudiante)
            $table->string('est_nombre');
            $table->string('est_apellidos');
            $table->string('est_doc')->nullable();
            $table->string('est_telefono')->nullable();
            $table->string('est_correo')->nullable();
            $table->unsignedBigInteger('est_ingreso')->default(0);

            // Codeudor
            $table->string('cod_nombre')->nullable();
            $table->string('cod_apellidos')->nullable();
            $table->string('cod_doc')->nullable();
            $table->string('cod_relacion')->nullable();
            $table->string('cod_actividad')->nullable();
            $table->unsignedBigInteger('cod_ingreso')->default(0);
            $table->unsignedBigInteger('cod_egresos')->default(0);
            $table->unsignedBigInteger('cod_otras_deudas')->default(0);

            // Crédito
            $table->foreignId('programa_id')->nullable()->constrained('programas')->nullOnDelete();
            $table->string('programa_nombre');
            $table->unsignedBigInteger('matricula');
            $table->unsignedTinyInteger('cuota_inicial_pct');
            $table->unsignedBigInteger('cuota_inicial');
            $table->unsignedBigInteger('saldo');
            $table->unsignedTinyInteger('n_cuotas');
            $table->unsignedBigInteger('cuota');
            $table->unsignedBigInteger('total_credito');
            $table->date('fecha_matricula')->nullable();
            $table->date('fecha_primera_cuota')->nullable();

            // Documentos de soporte (enlaces)
            $table->json('documentos')->nullable();
            $table->unsignedTinyInteger('docs_completos')->default(0);
            $table->unsignedTinyInteger('docs_requeridos')->default(0);

            // Resultado
            $table->enum('decision', ['VERDE', 'AMARILLO', 'ROJO']);
            $table->json('razones')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudios');
    }
};
