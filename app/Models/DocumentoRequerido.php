<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentoRequerido extends Model
{
    protected $fillable = ['clave', 'nombre', 'siempre_requerido', 'actividades', 'orden'];

    protected $casts = [
        'siempre_requerido' => 'boolean',
        'actividades' => 'array',
    ];

    public function esRequerido(?string $actividad): bool
    {
        if ($this->siempre_requerido) {
            return true;
        }

        return in_array($actividad, $this->actividades ?? [], true);
    }
}
