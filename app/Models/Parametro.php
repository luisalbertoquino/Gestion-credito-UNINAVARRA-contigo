<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    protected $fillable = [
        'umbral_verde', 'umbral_amarillo', 'min_cobertura',
        'cuota_inicial_minima_pct', 'min_cuotas', 'max_cuotas', 'tasa_mensual',
    ];

    protected $casts = [
        'min_cobertura' => 'float',
        'tasa_mensual' => 'float',
    ];

    public static function actual(): self
    {
        return static::query()->firstOrCreate([]);
    }
}
