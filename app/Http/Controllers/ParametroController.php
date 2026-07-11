<?php

namespace App\Http\Controllers;

use App\Models\Parametro;
use App\Models\Programa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ParametroController extends Controller
{
    /**
     * Valores de fábrica, iguales a los usados por ParametroSeeder / PARAMS_DEFAULT del prototipo.
     */
    private const DEFAULTS = [
        'umbral_verde' => 30,
        'umbral_amarillo' => 40,
        'min_cobertura' => 2,
        'cuota_inicial_minima_pct' => 50,
        'min_cuotas' => 1,
        'max_cuotas' => 6,
        'tasa_mensual' => 0,
    ];

    private const PROGRAMAS_DEFAULT = [
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

    public function edit(): View
    {
        return view('parametros.edit', [
            'params' => Parametro::actual(),
            'programas' => Programa::orderBy('orden')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cuota_inicial_minima_pct' => ['required', 'numeric', 'min:0', 'max:100'],
            'tasa_mensual' => ['required', 'numeric', 'min:0'],
            'min_cuotas' => ['required', 'integer', 'min:1'],
            'max_cuotas' => ['required', 'integer', 'min:1'],
            'umbral_verde' => ['required', 'numeric', 'min:0', 'max:100'],
            'umbral_amarillo' => ['required', 'numeric', 'min:0', 'max:100'],
            'min_cobertura' => ['required', 'numeric', 'min:0'],
            'programas' => ['required', 'array', 'min:1'],
            'programas.*.id' => ['nullable', 'integer', 'exists:programas,id'],
            'programas.*.grupo' => ['required', 'in:Pregrado,Posgrado'],
            'programas.*.nombre' => ['required', 'string', 'max:255'],
            'programas.*.matricula' => ['required', 'numeric', 'min:1'],
        ]);

        if ((int) $data['max_cuotas'] < (int) $data['min_cuotas']) {
            return back()->withInput()->with('error', 'El rango de cuotas no es válido: revisa el mínimo y el máximo.');
        }

        if ((float) $data['umbral_amarillo'] <= (float) $data['umbral_verde']) {
            return back()->withInput()->with('error', 'El umbral "límite" debe ser mayor que el umbral "aprobado".');
        }

        DB::transaction(function () use ($data) {
            Parametro::actual()->update([
                'cuota_inicial_minima_pct' => $data['cuota_inicial_minima_pct'],
                'tasa_mensual' => $data['tasa_mensual'],
                'min_cuotas' => $data['min_cuotas'],
                'max_cuotas' => $data['max_cuotas'],
                'umbral_verde' => $data['umbral_verde'],
                'umbral_amarillo' => $data['umbral_amarillo'],
                'min_cobertura' => $data['min_cobertura'],
            ]);

            $idsEnviados = [];

            foreach ($data['programas'] as $i => $p) {
                $programa = Programa::updateOrCreate(
                    ['id' => $p['id'] ?? null],
                    [
                        'grupo' => $p['grupo'],
                        'nombre' => trim($p['nombre']),
                        'matricula' => (int) $p['matricula'],
                        'orden' => $i,
                    ]
                );
                $idsEnviados[] = $programa->id;
            }

            Programa::whereNotIn('id', $idsEnviados)->delete();
        });

        return back()->with('status', 'Parámetros guardados. Los nuevos estudios usarán estos valores.');
    }

    public function restaurar(): RedirectResponse
    {
        DB::transaction(function () {
            Parametro::actual()->update(self::DEFAULTS);

            Programa::query()->delete();
            foreach (self::PROGRAMAS_DEFAULT as $i => $p) {
                Programa::create($p + ['orden' => $i]);
            }
        });

        return back()->with('status', 'Se restauraron los valores originales. El historial de estudios no se vio afectado.');
    }
}
