<?php

namespace Database\Seeders;

use App\Models\DocumentoRequerido;
use Illuminate\Database\Seeder;

class DocumentoRequeridoSeeder extends Seeder
{
    public function run(): void
    {
        $documentos = [
            ['clave' => 'ced_deudor', 'nombre' => 'Cédula del estudiante (deudor)', 'siempre_requerido' => true, 'actividades' => null],
            ['clave' => 'ced_codeudor', 'nombre' => 'Cédula del codeudor (deudor solidario)', 'siempre_requerido' => true, 'actividades' => null],
            ['clave' => 'cert_laboral', 'nombre' => 'Certificado laboral / de ingresos', 'siempre_requerido' => false, 'actividades' => ['Empleado']],
            ['clave' => 'colillas', 'nombre' => 'Desprendibles de nómina (últimos meses)', 'siempre_requerido' => false, 'actividades' => ['Empleado']],
            ['clave' => 'renta', 'nombre' => 'Declaración de renta', 'siempre_requerido' => false, 'actividades' => ['Independiente', 'Comerciante']],
            ['clave' => 'extractos', 'nombre' => 'Extractos bancarios', 'siempre_requerido' => false, 'actividades' => ['Independiente', 'Comerciante']],
            ['clave' => 'camara_rut', 'nombre' => 'Cámara de comercio / RUT', 'siempre_requerido' => false, 'actividades' => ['Independiente', 'Comerciante']],
            ['clave' => 'pension', 'nombre' => 'Certificado / colilla de pensión', 'siempre_requerido' => false, 'actividades' => ['Pensionado']],
            ['clave' => 'otros', 'nombre' => 'Otros documentos de soporte', 'siempre_requerido' => false, 'actividades' => []],
        ];

        foreach ($documentos as $i => $d) {
            DocumentoRequerido::updateOrCreate(
                ['clave' => $d['clave']],
                $d + ['orden' => $i]
            );
        }
    }
}
