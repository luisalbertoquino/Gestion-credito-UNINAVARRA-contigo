<?php

namespace Database\Seeders;

use App\Models\Programa;
use Illuminate\Database\Seeder;

class ProgramaSeeder extends Seeder
{
    public function run(): void
    {
        $programas = [
            ['grupo' => 'Pregrado', 'nombre' => 'Medicina', 'matricula' => 18101337],
            ['grupo' => 'Pregrado', 'nombre' => 'Enfermería', 'matricula' => 7637996],
            ['grupo' => 'Pregrado', 'nombre' => 'Tecnología en Radiología e Imágenes Diagnósticas', 'matricula' => 4995122],
            ['grupo' => 'Pregrado', 'nombre' => 'Derecho', 'matricula' => 5786395],
            ['grupo' => 'Pregrado', 'nombre' => 'Ingeniería Industrial', 'matricula' => 5108648],
            ['grupo' => 'Pregrado', 'nombre' => 'Ingeniería Ambiental', 'matricula' => 5108648],
            ['grupo' => 'Pregrado', 'nombre' => 'Administración de Empresas', 'matricula' => 5084168],
            ['grupo' => 'Pregrado', 'nombre' => 'Tecnología en Servicios de la Salud', 'matricula' => 2718645],
            ['grupo' => 'Pregrado', 'nombre' => 'Biología', 'matricula' => 4624047],
            ['grupo' => 'Posgrado', 'nombre' => 'Esp. Derecho Constitucional y Sistema Interamericano de DDHH', 'matricula' => 16708166],
            ['grupo' => 'Posgrado', 'nombre' => 'Esp. Derecho Médico', 'matricula' => 17057703],
            ['grupo' => 'Posgrado', 'nombre' => 'Esp. Docencia Universitaria', 'matricula' => 17057703],
            ['grupo' => 'Posgrado', 'nombre' => 'Esp. Litigación Oral', 'matricula' => 17057703],
            ['grupo' => 'Posgrado', 'nombre' => 'Esp. Gerencia de Seguridad y Salud en el Trabajo', 'matricula' => 17057703],
            ['grupo' => 'Posgrado', 'nombre' => 'Esp. Derecho Laboral y Seguridad Social', 'matricula' => 17057703],
            ['grupo' => 'Posgrado', 'nombre' => 'Esp. Medicina Familiar', 'matricula' => 17060964],
            ['grupo' => 'Posgrado', 'nombre' => 'Esp. Medicina Interna', 'matricula' => 48007956],
        ];

        foreach ($programas as $i => $p) {
            Programa::create($p + ['orden' => $i]);
        }
    }
}
