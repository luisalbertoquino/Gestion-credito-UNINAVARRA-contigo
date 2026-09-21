<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $fillable = ['grupo', 'nombre', 'matricula', 'orden', 'cuota_inicial_pct', 'tasa_mensual'];

    protected $casts = [
        'matricula' => 'integer',
        'orden' => 'integer',
        'cuota_inicial_pct' => 'integer',
        'tasa_mensual' => 'float',
    ];

    /**
     * Cuota inicial mínima efectiva: usa el override del programa si existe,
     * o el valor global de Parametro en caso contrario.
     */
    public function cuotaInicialPct(Parametro $params): float
    {
        return $this->cuota_inicial_pct !== null
            ? (float) $this->cuota_inicial_pct
            : (float) $params->cuota_inicial_minima_pct;
    }

    /**
     * Tasa mensual efectiva: usa el override del programa si existe,
     * o el valor global de Parametro en caso contrario.
     */
    public function tasaMensual(Parametro $params): float
    {
        return $this->tasa_mensual !== null
            ? (float) $this->tasa_mensual
            : (float) $params->tasa_mensual;
    }
}
