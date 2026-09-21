<?php

namespace App\Http\Controllers;

use App\Models\DocumentoRequerido;
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
        'nombre_institucion' => 'UNINAVARRA Contigo',
        'texto_verde' => 'APROBADO',
        'subtexto_verde' => 'Cumple los criterios del estudio de crédito',
        'texto_amarillo' => 'REQUIERE REVISIÓN',
        'subtexto_amarillo' => 'Aprobable con garantía o análisis adicional',
        'texto_rojo' => 'NO VIABLE',
        'subtexto_rojo' => 'No cumple la capacidad de pago requerida',
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

    private const DOCUMENTOS_DEFAULT = [
        ['clave' => 'ced_deudor', 'nombre' => 'Cédula del estudiante (deudor)', 'siempre_requerido' => true, 'actividades' => []],
        ['clave' => 'ced_codeudor', 'nombre' => 'Cédula del codeudor (deudor solidario)', 'siempre_requerido' => true, 'actividades' => []],
        ['clave' => 'cert_laboral', 'nombre' => 'Certificado laboral / de ingresos', 'siempre_requerido' => false, 'actividades' => ['Empleado']],
        ['clave' => 'colillas', 'nombre' => 'Desprendibles de nómina (últimos meses)', 'siempre_requerido' => false, 'actividades' => ['Empleado']],
        ['clave' => 'renta', 'nombre' => 'Declaración de renta', 'siempre_requerido' => false, 'actividades' => ['Independiente', 'Comerciante']],
        ['clave' => 'extractos', 'nombre' => 'Extractos bancarios', 'siempre_requerido' => false, 'actividades' => ['Independiente', 'Comerciante']],
        ['clave' => 'camara_rut', 'nombre' => 'Cámara de comercio / RUT', 'siempre_requerido' => false, 'actividades' => ['Independiente', 'Comerciante']],
        ['clave' => 'pension', 'nombre' => 'Certificado / colilla de pensión', 'siempre_requerido' => false, 'actividades' => ['Pensionado']],
        ['clave' => 'otros', 'nombre' => 'Otros documentos de soporte', 'siempre_requerido' => false, 'actividades' => []],
    ];

    public function edit(): View
    {
        return view('parametros.edit', [
            'params' => Parametro::actual(),
            'programas' => Programa::orderBy('orden')->get(),
            'documentos' => DocumentoRequerido::orderBy('orden')->get(),
            'actividadesDisponibles' => ['Empleado', 'Independiente', 'Comerciante', 'Pensionado'],
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
            'nombre_institucion' => ['required', 'string', 'max:255'],
            'texto_verde' => ['required', 'string', 'max:100'],
            'subtexto_verde' => ['required', 'string', 'max:255'],
            'texto_amarillo' => ['required', 'string', 'max:100'],
            'subtexto_amarillo' => ['required', 'string', 'max:255'],
            'texto_rojo' => ['required', 'string', 'max:100'],
            'subtexto_rojo' => ['required', 'string', 'max:255'],
            'campos_visibles' => ['nullable', 'array'],
            'programas' => ['required', 'array', 'min:1'],
            'programas.*.id' => ['nullable', 'integer', 'exists:programas,id'],
            'programas.*.grupo' => ['required', 'in:Pregrado,Posgrado'],
            'programas.*.nombre' => ['required', 'string', 'max:255'],
            'programas.*.matricula' => ['required', 'numeric', 'min:1'],
            'programas.*.cuota_inicial_pct' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'programas.*.tasa_mensual' => ['nullable', 'numeric', 'min:0'],
            'documentos' => ['required', 'array', 'min:1'],
            'documentos.*.id' => ['nullable', 'integer', 'exists:documento_requeridos,id'],
            'documentos.*.nombre' => ['required', 'string', 'max:255'],
            'documentos.*.siempre_requerido' => ['nullable'],
            'documentos.*.actividades' => ['nullable', 'array'],
        ]);

        if ((int) $data['max_cuotas'] < (int) $data['min_cuotas']) {
            return back()->withInput()->with('error', 'El rango de cuotas no es válido: revisa el mínimo y el máximo.');
        }

        if ((float) $data['umbral_amarillo'] <= (float) $data['umbral_verde']) {
            return back()->withInput()->with('error', 'El umbral "límite" debe ser mayor que el umbral "aprobado".');
        }

        $camposVisibles = [];
        foreach (array_keys(Parametro::CAMPOS_OPCIONALES) as $campo) {
            $camposVisibles[$campo] = $request->boolean("campos_visibles.$campo");
        }

        DB::transaction(function () use ($data, $camposVisibles) {
            Parametro::actual()->update([
                'cuota_inicial_minima_pct' => $data['cuota_inicial_minima_pct'],
                'tasa_mensual' => $data['tasa_mensual'],
                'min_cuotas' => $data['min_cuotas'],
                'max_cuotas' => $data['max_cuotas'],
                'umbral_verde' => $data['umbral_verde'],
                'umbral_amarillo' => $data['umbral_amarillo'],
                'min_cobertura' => $data['min_cobertura'],
                'nombre_institucion' => trim($data['nombre_institucion']),
                'texto_verde' => trim($data['texto_verde']),
                'subtexto_verde' => trim($data['subtexto_verde']),
                'texto_amarillo' => trim($data['texto_amarillo']),
                'subtexto_amarillo' => trim($data['subtexto_amarillo']),
                'texto_rojo' => trim($data['texto_rojo']),
                'subtexto_rojo' => trim($data['subtexto_rojo']),
                'campos_visibles' => $camposVisibles,
            ]);

            $idsProgramas = [];
            foreach ($data['programas'] as $i => $p) {
                $programa = Programa::updateOrCreate(
                    ['id' => $p['id'] ?? null],
                    [
                        'grupo' => $p['grupo'],
                        'nombre' => trim($p['nombre']),
                        'matricula' => (int) $p['matricula'],
                        'orden' => $i,
                        'cuota_inicial_pct' => $p['cuota_inicial_pct'] !== '' && $p['cuota_inicial_pct'] !== null
                            ? (int) $p['cuota_inicial_pct'] : null,
                        'tasa_mensual' => $p['tasa_mensual'] !== '' && $p['tasa_mensual'] !== null
                            ? (float) $p['tasa_mensual'] : null,
                    ]
                );
                $idsProgramas[] = $programa->id;
            }
            Programa::whereNotIn('id', $idsProgramas)->delete();

            $idsDocumentos = [];
            foreach ($data['documentos'] as $i => $d) {
                $clave = $d['id'] ?? null
                    ? DocumentoRequerido::find($d['id'])?->clave
                    : \Illuminate\Support\Str::slug($d['nombre'], '_');

                $documento = DocumentoRequerido::updateOrCreate(
                    ['id' => $d['id'] ?? null],
                    [
                        'clave' => $clave ?: ('doc_'.$i),
                        'nombre' => trim($d['nombre']),
                        'siempre_requerido' => ! empty($d['siempre_requerido']),
                        'actividades' => array_values($d['actividades'] ?? []),
                        'orden' => $i,
                    ]
                );
                $idsDocumentos[] = $documento->id;
            }
            DocumentoRequerido::whereNotIn('id', $idsDocumentos)->delete();
        });

        return back()->with('status', 'Parámetros guardados. Los nuevos estudios usarán estos valores.');
    }

    public function restaurar(): RedirectResponse
    {
        DB::transaction(function () {
            Parametro::actual()->update(self::DEFAULTS + ['campos_visibles' => []]);

            Programa::query()->delete();
            foreach (self::PROGRAMAS_DEFAULT as $i => $p) {
                Programa::create($p + ['orden' => $i]);
            }

            DocumentoRequerido::query()->delete();
            foreach (self::DOCUMENTOS_DEFAULT as $i => $d) {
                DocumentoRequerido::create($d + ['orden' => $i]);
            }
        });

        return back()->with('status', 'Se restauraron los valores originales. El historial de estudios no se vio afectado.');
    }
}
