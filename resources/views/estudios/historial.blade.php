@extends('layouts.creditos')

@section('title', 'Historial de estudios')

@section('content')
<section class="panel" id="panel-historial">
  <p class="eyebrow-lg"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/></svg>Registro histórico</p>
  <h2 class="sec" style="font-family:var(--serif);font-size:24px;margin-bottom:6px">Estudios guardados</h2>
  <p class="lede">Todos los estudios de crédito guardados en el sistema, con su decisión y datos clave. Descárgalos en CSV para llevar el consolidado a Excel.</p>

  <div class="hist-toolbar">
    <span class="hist-count">{{ $estudios->count() }} estudio{{ $estudios->count() === 1 ? '' : 's' }} guardado{{ $estudios->count() === 1 ? '' : 's' }}</span>
    <a class="btn gold sm" href="{{ route('estudios.exportar') }}" style="margin-left:auto">
      <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
      Descargar histórico (CSV)
    </a>
  </div>

  <div class="card">
    <div style="overflow-x:auto">
      <table class="hist">
        <thead><tr><th>Fecha</th><th>Estudiante</th><th>Documento</th><th>Programa</th><th>Decisión</th><th>Cuota inicial</th><th>Cuota mensual</th><th>Cuotas</th><th>Documentos</th><th></th></tr></thead>
        <tbody>
          @foreach ($estudios as $e)
            <tr>
              <td>{{ $e->created_at?->format('d/m/Y') }}</td>
              <td>{{ $e->nombreEstudiante() }}</td>
              <td>{{ $e->est_doc ?: '—' }}</td>
              <td>{{ $e->programa_nombre }}</td>
              <td>
                @php $txt = \App\Services\EstudioCalculadora::decisionTexto($e->decision); @endphp
                <span class="badge {{ $e->decision }}">{{ $txt['txt'] }}</span>
              </td>
              <td>$ {{ number_format($e->cuota_inicial, 0, ',', '.') }}</td>
              <td>$ {{ number_format($e->cuota, 0, ',', '.') }}</td>
              <td>{{ $e->n_cuotas }}</td>
              <td>{{ $e->docs_completos }}/{{ $e->docs_requeridos }}</td>
              <td>
                <form method="POST" action="{{ route('estudios.destroy', $e) }}" onsubmit="return confirm('¿Eliminar este estudio del historial?');">
                  @csrf
                  @method('DELETE')
                  <button class="btn danger sm" type="submit">Eliminar</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if ($estudios->isEmpty())
      <div class="empty-hist">Aún no hay estudios guardados. Completa un estudio en la pestaña "Nuevo estudio" y presiona "Guardar".</div>
    @endif
  </div>
</section>
@endsection
