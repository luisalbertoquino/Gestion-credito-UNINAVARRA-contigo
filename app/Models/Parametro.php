<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    protected $fillable = [
        'umbral_verde', 'umbral_amarillo', 'min_cobertura',
        'cuota_inicial_minima_pct', 'min_cuotas', 'max_cuotas', 'tasa_mensual',
        'nombre_institucion',
        'texto_verde', 'subtexto_verde',
        'texto_amarillo', 'subtexto_amarillo',
        'texto_rojo', 'subtexto_rojo',
        'campos_visibles',
    ];

    protected $casts = [
        'min_cobertura' => 'float',
        'tasa_mensual' => 'float',
        'campos_visibles' => 'array',
    ];

    /**
     * Campos opcionales del formulario que se pueden ocultar sin afectar el cálculo.
     * La clave es el nombre del input; el valor por defecto es "visible".
     */
    public const CAMPOS_OPCIONALES = [
        'est_telefono' => 'Teléfono del estudiante',
        'est_correo' => 'Correo del estudiante',
        'cod_relacion' => 'Relación del codeudor con el estudiante',
        'cod_otras_deudas' => 'Otras deudas del codeudor',
        'fecha_primera_cuota' => 'Fecha de la primera cuota (manual)',
    ];

    public static function actual(): self
    {
        return static::query()->firstOrCreate([]);
    }

    public function campoVisible(string $campo): bool
    {
        $visibles = $this->campos_visibles;

        if (! is_array($visibles) || ! array_key_exists($campo, $visibles)) {
            return true;
        }

        return (bool) $visibles[$campo];
    }

    public function textoDecision(string $nivel): array
    {
        return match ($nivel) {
            'VERDE' => ['txt' => $this->texto_verde, 'sub' => $this->subtexto_verde],
            'AMARILLO' => ['txt' => $this->texto_amarillo, 'sub' => $this->subtexto_amarillo],
            default => ['txt' => $this->texto_rojo, 'sub' => $this->subtexto_rojo],
        };
    }
}
