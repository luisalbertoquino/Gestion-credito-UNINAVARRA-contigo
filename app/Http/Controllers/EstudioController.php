<?php

namespace App\Http\Controllers;

use App\Models\Estudio;
use App\Models\Parametro;
use App\Models\Programa;
use App\Services\EstudioCalculadora;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EstudioController extends Controller
{
    public function index(): View
    {
        $params = Parametro::actual();
        $programas = Programa::orderBy('orden')->get();

        return view('estudios.index', [
            'params' => $params,
            'programas' => $programas,
            'documentos' => EstudioCalculadora::documentos(),
        ]);
    }

    public function calcular(Request $request)
    {
        $data = $this->datosFormulario($request);

        $params = Parametro::actual();
        $programa = Programa::find($data['programa_id']) ?? Programa::orderBy('orden')->first();

        if (! $programa) {
            return response()->json(['error' => 'No hay programas registrados.'], 422);
        }

        $calc = EstudioCalculadora::calcularCredito($params, $programa, $data);
        $docsInfo = EstudioCalculadora::evaluarDocumentos($data['cod_actividad'] ?? null, $data['documentos'] ?? []);

        $payload = [
            'valido' => $calc['params']['valido'],
            'pctValido' => $calc['params']['pctValido'],
            'cuotasValido' => $calc['params']['cuotasValido'],
        ];

        if (! $calc['params']['valido']) {
            return response()->json($payload + [
                'hintCuotaInicialMinima' => (int) $programa->cuotaInicialPct($params),
                'hintMinCuotas' => (int) $params->min_cuotas,
                'hintMaxCuotas' => (int) $params->max_cuotas,
                'docsInfo' => $docsInfo,
            ]);
        }

        $decision = EstudioCalculadora::calcularDecision($params, $calc, $docsInfo);
        $decisionTxt = EstudioCalculadora::decisionTexto($decision['nivel'], $params);

        return response()->json($payload + [
            'decision' => $decision['nivel'],
            'decisionTxt' => $decisionTxt['txt'],
            'decisionSub' => $decisionTxt['sub'],
            'razones' => $decision['razones'],
            'kpis' => [
                'disponible' => $calc['disponible'],
                'endeudamiento' => is_infinite($calc['endeudamiento']) ? null : round($calc['endeudamiento'], 1),
                'cuota' => $calc['cuota'],
                'cobertura' => round($calc['cobertura'], 1),
            ],
            'programa' => [
                'id' => $programa->id,
                'nombre' => $programa->nombre,
                'grupo' => $programa->grupo,
                'matricula' => $programa->matricula,
                'cuotaInicialPct' => $programa->cuotaInicialPct($params),
                'tasaMensual' => $programa->tasaMensual($params),
            ],
            'resumen' => [
                'pct' => $calc['params']['pct'],
                'matricula' => $calc['matricula'],
                'cuotaInicial' => $calc['cuotaInicial'],
                'saldo' => $calc['saldo'],
                'nCuotas' => $calc['nCuotas'],
                'cuota' => $calc['cuota'],
                'totalCredito' => $calc['totalCredito'],
            ],
            'tagCuotas' => $calc['nCuotas'].' cuota'.($calc['nCuotas'] === 1 ? '' : 's'),
            'plan' => [
                'fechaInicial' => $calc['fechaMatricula']->toDateString(),
                'cuotaInicial' => $calc['cuotaInicial'],
                'saldoInicial' => $calc['saldo'],
                'filas' => array_map(fn ($f) => [
                    'n' => $f['n'],
                    'nCuotas' => $calc['nCuotas'],
                    'fecha' => $f['fecha']->toDateString(),
                    'cuota' => $f['cuota'],
                    'saldo' => $f['saldo'],
                ], $calc['tabla']),
                'totalCredito' => $calc['totalCredito'],
            ],
            'docsInfo' => $docsInfo,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'est_nombre' => ['required', 'string', 'max:255'],
            'est_apellidos' => ['required', 'string', 'max:255'],
            'programa_id' => ['required', 'exists:programas,id'],
            'cuotaInicialPct' => ['required', 'numeric'],
            'numCuotas' => ['required', 'integer'],
        ]);

        $data = $this->datosFormulario($request);

        $params = Parametro::actual();
        $programa = Programa::findOrFail($data['programa_id']);

        $calc = EstudioCalculadora::calcularCredito($params, $programa, $data);

        if (! $calc['params']['valido']) {
            return back()->withInput()->with('error', 'Corrige la cuota inicial o el número de cuotas antes de guardar: están fuera del rango permitido.');
        }

        $docsInfo = EstudioCalculadora::evaluarDocumentos($data['cod_actividad'] ?? null, $data['documentos'] ?? []);
        $decision = EstudioCalculadora::calcularDecision($params, $calc, $docsInfo);

        $estudio = Estudio::create([
            'est_nombre' => $data['est_nombre'],
            'est_apellidos' => $data['est_apellidos'],
            'est_doc' => $data['est_doc'] ?? null,
            'est_telefono' => $data['est_telefono'] ?? null,
            'est_correo' => $data['est_correo'] ?? null,
            'est_ingreso' => $calc['ingresoEstudiante'],
            'cod_nombre' => $data['cod_nombre'] ?? null,
            'cod_apellidos' => $data['cod_apellidos'] ?? null,
            'cod_doc' => $data['cod_doc'] ?? null,
            'cod_relacion' => $data['cod_relacion'] ?? null,
            'cod_actividad' => $data['cod_actividad'] ?? null,
            'cod_ingreso' => $calc['ingresoCodeudor'],
            'cod_egresos' => $data['cod_egresos'] ?? 0,
            'cod_otras_deudas' => $data['cod_otras_deudas'] ?? 0,
            'programa_id' => $programa->id,
            'programa_nombre' => $programa->nombre,
            'matricula' => $calc['matricula'],
            'cuota_inicial_pct' => $calc['params']['pct'],
            'cuota_inicial' => $calc['cuotaInicial'],
            'saldo' => $calc['saldo'],
            'n_cuotas' => $calc['nCuotas'],
            'cuota' => (int) round($calc['cuota']),
            'total_credito' => (int) round($calc['totalCredito']),
            'fecha_matricula' => $data['fecha_matricula'] ?? null,
            'fecha_primera_cuota' => $data['fecha_primera_cuota'] ?? null,
            'documentos' => $data['documentos'] ?? [],
            'docs_completos' => $docsInfo['completos'],
            'docs_requeridos' => $docsInfo['requeridos'],
            'decision' => $decision['nivel'],
            'razones' => $decision['razones'],
            'user_id' => $request->user()?->id,
        ]);

        $decisionTxt = EstudioCalculadora::decisionTexto($decision['nivel'], $params);

        return redirect()->route('estudios.index')
            ->with('status', 'Estudio guardado: '.$estudio->nombreEstudiante().' — '.$decisionTxt['txt']);
    }

    public function historial(): View
    {
        $estudios = Estudio::orderByDesc('created_at')->get();

        return view('estudios.historial', [
            'estudios' => $estudios,
        ]);
    }

    public function destroy(Estudio $estudio): RedirectResponse
    {
        $estudio->delete();

        return back()->with('status', 'Estudio eliminado del historial.');
    }

    public function exportarCsv(): StreamedResponse
    {
        $estudios = Estudio::orderByDesc('created_at')->get();
        $params = Parametro::actual();

        $filename = 'historico-estudios-credito-uninavarra-'.now()->format('Y-m-d').'.csv';

        $callback = function () use ($estudios, $params) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM UTF-8

            fputcsv($out, [
                'Fecha', 'Estudiante', 'Documento estudiante', 'Codeudor', 'Documento codeudor',
                'Programa', 'Decisión', 'Cuota inicial (%)', 'Valor cuota inicial', 'Valor cuota mensual',
                'Número de cuotas', 'Total del crédito', 'Documentos completos', 'Documentos requeridos',
            ], ';');

            foreach ($estudios as $e) {
                $decisionTxt = EstudioCalculadora::decisionTexto($e->decision, $params);
                fputcsv($out, [
                    $e->created_at?->format('d/m/Y H:i'),
                    $e->nombreEstudiante(),
                    $e->est_doc,
                    $e->nombreCodeudor(),
                    $e->cod_doc,
                    $e->programa_nombre,
                    $decisionTxt['txt'],
                    $e->cuota_inicial_pct,
                    (int) $e->cuota_inicial,
                    (int) $e->cuota,
                    $e->n_cuotas,
                    (int) $e->total_credito,
                    $e->docs_completos,
                    $e->docs_requeridos,
                ], ';');
            }

            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Normaliza los campos del request (form o JSON) a un array plano
     * usado por EstudioCalculadora.
     */
    private function datosFormulario(Request $request): array
    {
        $documentos = $request->input('documentos', []);
        if (! is_array($documentos)) {
            $documentos = [];
        }

        return [
            'programa_id' => (int) $request->input('programa_id'),
            'est_nombre' => trim((string) $request->input('est_nombre', '')),
            'est_apellidos' => trim((string) $request->input('est_apellidos', '')),
            'est_doc' => $request->input('est_doc'),
            'est_telefono' => $request->input('est_telefono'),
            'est_correo' => $request->input('est_correo'),
            'est_ingreso' => (float) $request->input('est_ingreso', 0),
            'cod_nombre' => $request->input('cod_nombre'),
            'cod_apellidos' => $request->input('cod_apellidos'),
            'cod_doc' => $request->input('cod_doc'),
            'cod_relacion' => $request->input('cod_relacion'),
            'cod_actividad' => $request->input('cod_actividad'),
            'cod_ingreso' => (float) $request->input('cod_ingreso', 0),
            'cod_egresos' => (float) $request->input('cod_egresos', 0),
            'cod_otras_deudas' => (float) $request->input('cod_otrasDeudas', $request->input('cod_otras_deudas', 0)),
            'cuota_inicial_pct' => (float) $request->input('cuotaInicialPct', $request->input('cuota_inicial_pct', 0)),
            'n_cuotas' => (int) $request->input('numCuotas', $request->input('n_cuotas', 0)),
            'fecha_matricula' => $request->input('fechaMatricula', $request->input('fecha_matricula')),
            'fecha_primera_cuota' => $request->input('fechaPrimeraCuota', $request->input('fecha_primera_cuota')),
            'documentos' => $documentos,
        ];
    }
}
