<?php

namespace App\Services;

use App\Models\DocumentoRequerido;
use App\Models\Parametro;
use App\Models\Programa;
use Carbon\Carbon;

class EstudioCalculadora
{
    /**
     * Catálogo de documentos requeridos, configurable desde Parámetros
     * (tabla documento_requeridos), con su condición según la actividad
     * económica del codeudor.
     */
    public static function documentos(): \Illuminate\Support\Collection
    {
        return DocumentoRequerido::orderBy('orden')->get();
    }

    /**
     * Evalúa el estado de los documentos (enlaces) frente a la actividad económica.
     * $documentos: [clave => url]
     */
    public static function evaluarDocumentos(?string $actividad, array $documentos): array
    {
        $requeridos = 0;
        $completos = 0;
        $detalle = [];

        foreach (self::documentos() as $doc) {
            $esRequerido = $doc->esRequerido($actividad);
            $link = trim((string) ($documentos[$doc->clave] ?? ''));
            $esValido = (bool) preg_match('/^https?:\/\/.+/', $link);

            if ($esRequerido) {
                $requeridos++;
                if ($esValido) {
                    $completos++;
                }
            }

            $detalle[] = [
                'id' => $doc->clave,
                'nombre' => $doc->nombre,
                'requerido' => $esRequerido,
                'link' => $link,
                'valido' => $esValido,
            ];
        }

        return [
            'requeridos' => $requeridos,
            'completos' => $completos,
            'faltan' => $requeridos - $completos,
            'detalle' => $detalle,
        ];
    }

    /**
     * Valida cuota inicial (%) y número de cuotas contra los parámetros vigentes.
     * La cuota inicial mínima puede estar sobreescrita por programa.
     * Replica validarParametrosCredito() de app.js.
     */
    public static function validarParametrosCredito(Parametro $params, Programa $programa, float $pct, int $nCuotas): array
    {
        $cuotaInicialMinima = $programa->cuotaInicialPct($params);

        $pctValido = $pct >= $cuotaInicialMinima && $pct <= 100;
        $cuotasValido = $nCuotas >= $params->min_cuotas && $nCuotas <= $params->max_cuotas;

        return [
            'pct' => $pctValido ? $pct : $cuotaInicialMinima,
            'nCuotas' => $cuotasValido ? $nCuotas : min(max($nCuotas, $params->min_cuotas), $params->max_cuotas),
            'pctValido' => $pctValido,
            'cuotasValido' => $cuotasValido,
            'valido' => $pctValido && $cuotasValido,
        ];
    }

    /**
     * Cálculo de amortización de cuota fija. Replica calcularCredito() de app.js.
     *
     * $datos espera las llaves: programa_id|programa_nombre|matricula,
     * cuota_inicial_pct, n_cuotas, fecha_matricula,
     * est_ingreso, cod_ingreso, cod_egresos, cod_otras_deudas.
     */
    public static function calcularCredito(Parametro $params, Programa $programa, array $datos): array
    {
        $pctSolicitado = (float) ($datos['cuota_inicial_pct'] ?? 0);
        $nCuotasSolicitado = (int) ($datos['n_cuotas'] ?? 0);

        $validacion = self::validarParametrosCredito($params, $programa, $pctSolicitado, $nCuotasSolicitado);

        $matricula = (int) $programa->matricula;
        $cuotaInicial = (int) round($matricula * ($validacion['pct'] / 100));
        $saldo = max($matricula - $cuotaInicial, 0);
        $tasa = $programa->tasaMensual($params) / 100;
        $nCuotas = (int) $validacion['nCuotas'];

        if ($tasa > 0) {
            $cuota = $saldo * $tasa / (1 - ($tasa + 1) ** (-$nCuotas));
        } else {
            $cuota = $nCuotas > 0 ? $saldo / $nCuotas : 0;
        }

        $fechaMatricula = $datos['fecha_matricula'] ?? null;
        $baseFecha = $fechaMatricula ? Carbon::parse($fechaMatricula) : Carbon::today();

        $tabla = [];
        $restante = $saldo;
        for ($i = 1; $i <= $nCuotas; $i++) {
            $interes = $tasa > 0 ? $restante * $tasa : 0;
            $abono = $cuota - $interes;
            $restante = max($restante - $abono, 0);
            $tabla[] = [
                'n' => $i,
                'fecha' => $baseFecha->copy()->addMonths($i - 1),
                'cuota' => $cuota,
                'saldo' => $restante,
            ];
        }

        $totalCuotas = $cuota * $nCuotas;
        $totalCredito = $cuotaInicial + $totalCuotas;

        $ingresoEstudiante = (float) ($datos['est_ingreso'] ?? 0);
        $ingresoCodeudor = (float) ($datos['cod_ingreso'] ?? 0);
        $egresosCodeudor = (float) ($datos['cod_egresos'] ?? 0);
        $otrasDeudas = (float) ($datos['cod_otras_deudas'] ?? 0);

        $ingresosTotales = $ingresoEstudiante + $ingresoCodeudor;
        $egresosTotales = $egresosCodeudor + $otrasDeudas;
        $disponible = $ingresosTotales - $egresosTotales;
        $endeudamiento = $disponible > 0 ? ($cuota / $disponible * 100) : INF;
        $cobertura = $cuota > 0 ? ($disponible / $cuota) : 0;

        return [
            'programa' => $programa,
            'params' => $validacion,
            'matricula' => $matricula,
            'cuotaInicial' => $cuotaInicial,
            'saldo' => $saldo,
            'cuota' => $cuota,
            'nCuotas' => $nCuotas,
            'totalCuotas' => $totalCuotas,
            'totalCredito' => $totalCredito,
            'tabla' => $tabla,
            'ingresosTotales' => $ingresosTotales,
            'egresosTotales' => $egresosTotales,
            'disponible' => $disponible,
            'endeudamiento' => $endeudamiento,
            'cobertura' => $cobertura,
            'fechaMatricula' => $baseFecha,
            'ingresoEstudiante' => $ingresoEstudiante,
            'ingresoCodeudor' => $ingresoCodeudor,
        ];
    }

    /**
     * Decisión de viabilidad VERDE/AMARILLO/ROJO. Replica calcularDecision() de app.js.
     */
    public static function calcularDecision(Parametro $params, array $calc, array $docsInfo): array
    {
        $nivel = 'VERDE';
        $razones = [];

        $ingresoCodeudor = $calc['ingresoCodeudor'];
        $ingresoEstudiante = $calc['ingresoEstudiante'];
        $disponible = $calc['disponible'];
        $endeudamiento = $calc['endeudamiento'];
        $cobertura = $calc['cobertura'];

        if ($disponible <= 0) {
            $nivel = 'ROJO';
            if ($ingresoEstudiante <= 0 && $ingresoCodeudor <= 0) {
                $razones[] = 'No se registran ingresos del deudor o codeudor.';
            }
            $razones[] = 'El ingreso disponible (ingresos − egresos) es cero o negativo.';
        } elseif ($endeudamiento > $params->umbral_amarillo) {
            $nivel = 'ROJO';
            $razones[] = 'La cuota representa el '.number_format($endeudamiento, 1).'% del ingreso disponible, por encima del máximo permitido ('.$params->umbral_amarillo.'%).';
        } elseif ($endeudamiento > $params->umbral_verde) {
            $nivel = 'AMARILLO';
            $razones[] = 'La cuota representa el '.number_format($endeudamiento, 1).'% del ingreso disponible (margen preferente: '.$params->umbral_verde.'%–'.$params->umbral_amarillo.'%).';
        } else {
            $razones[] = 'La cuota representa el '.number_format($endeudamiento, 1).'% del ingreso disponible, dentro del margen aprobado (≤ '.$params->umbral_verde.'%).';
        }

        if ($disponible > 0 && $cobertura < $params->min_cobertura && $nivel === 'VERDE') {
            $nivel = 'AMARILLO';
            $razones[] = 'La cobertura es de '.number_format($cobertura, 1).'× la cuota (mínimo sugerido: '.rtrim(rtrim(number_format((float) $params->min_cobertura, 2), '0'), '.').'×).';
        }

        if ($ingresoCodeudor <= 0 && $nivel === 'VERDE') {
            $nivel = 'AMARILLO';
            $razones[] = 'El codeudor no registra ingresos; se recomienda validar respaldo económico.';
        }

        if ($docsInfo['faltan'] > 0) {
            if ($nivel === 'VERDE') {
                $nivel = 'AMARILLO';
            }
            $razones[] = 'Expediente incompleto: faltan '.$docsInfo['faltan'].' de '.$docsInfo['requeridos'].' documentos requeridos para formalizar la aprobación.';
        } elseif ($docsInfo['requeridos'] > 0) {
            $razones[] = 'Expediente documental completo para la actividad económica del codeudor.';
        }

        return ['nivel' => $nivel, 'razones' => $razones];
    }

    public static function decisionTexto(string $nivel, ?Parametro $params = null): array
    {
        if ($params) {
            return $params->textoDecision($nivel);
        }

        return match ($nivel) {
            'VERDE' => ['txt' => 'APROBADO', 'sub' => 'Cumple los criterios del estudio de crédito'],
            'AMARILLO' => ['txt' => 'REQUIERE REVISIÓN', 'sub' => 'Aprobable con garantía o análisis adicional'],
            default => ['txt' => 'NO VIABLE', 'sub' => 'No cumple la capacidad de pago requerida'],
        };
    }
}
