<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estudio extends Model
{
    protected $fillable = [
        'est_nombre', 'est_apellidos', 'est_doc', 'est_telefono', 'est_correo', 'est_ingreso',
        'cod_nombre', 'cod_apellidos', 'cod_doc', 'cod_relacion', 'cod_actividad',
        'cod_ingreso', 'cod_egresos', 'cod_otras_deudas',
        'programa_id', 'programa_nombre', 'matricula', 'cuota_inicial_pct', 'cuota_inicial',
        'saldo', 'n_cuotas', 'cuota', 'total_credito', 'fecha_matricula', 'fecha_primera_cuota',
        'documentos', 'docs_completos', 'docs_requeridos', 'decision', 'razones', 'user_id',
    ];

    protected $casts = [
        'fecha_matricula' => 'date',
        'fecha_primera_cuota' => 'date',
        'documentos' => 'array',
        'razones' => 'array',
    ];

    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function nombreEstudiante(): string
    {
        return trim($this->est_nombre.' '.$this->est_apellidos);
    }

    public function nombreCodeudor(): string
    {
        return trim(($this->cod_nombre ?? '').' '.($this->cod_apellidos ?? ''));
    }
}
