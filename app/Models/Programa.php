<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    protected $fillable = ['grupo', 'nombre', 'matricula', 'orden'];

    protected $casts = [
        'matricula' => 'integer',
        'orden' => 'integer',
    ];
}
