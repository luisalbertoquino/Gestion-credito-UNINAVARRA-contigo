@extends('layouts.creditos')

@section('title', 'Parámetros')

@section('content')
<section class="panel" id="panel-parametros">
  <p class="eyebrow-lg"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2Z"/></svg>Configuración</p>
  <h2 class="sec" style="font-family:var(--serif);font-size:24px;margin-bottom:6px">Parámetros del plan</h2>
  <p class="lede">Estos valores no provienen de un acuerdo institucional automático — confírmalos con financiera y ajústalos aquí cuando cambien (por ejemplo, cada año con el reajuste de matrículas). Los cambios aplican de inmediato a los nuevos estudios.</p>

  <form method="POST" action="{{ route('parametros.update') }}" id="form-parametros">
    @csrf
    @method('PUT')

    <div class="card pad" style="margin-bottom:20px">
      <div class="head" style="padding:0;border:none;margin-bottom:16px">
        <div class="num"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
        <h2 style="font-size:16px">Condiciones de financiación</h2>
      </div>
      <div class="form-grid">
        <div class="field">
          <label>Cuota inicial mínima (%)</label>
          <input type="number" name="cuota_inicial_minima_pct" min="0" max="100" step="1" value="{{ old('cuota_inicial_minima_pct', $params->cuota_inicial_minima_pct) }}">
          <span class="hint">El sistema no deja registrar una cuota inicial menor a este porcentaje.</span>
        </div>
        <div class="field">
          <label>Tasa de financiación mensual (%)</label>
          <input type="number" name="tasa_mensual" min="0" step="0.1" value="{{ old('tasa_mensual', $params->tasa_mensual) }}">
          <span class="hint">0% si el plan no cobra interés corriente.</span>
        </div>
        <div class="field">
          <label>Cuotas mínimas</label>
          <input type="number" name="min_cuotas" min="1" step="1" value="{{ old('min_cuotas', $params->min_cuotas) }}">
        </div>
        <div class="field">
          <label>Cuotas máximas</label>
          <input type="number" name="max_cuotas" min="1" step="1" value="{{ old('max_cuotas', $params->max_cuotas) }}">
        </div>
      </div>
    </div>

    <div class="card pad" style="margin-bottom:20px">
      <div class="head" style="padding:0;border:none;margin-bottom:16px">
        <div class="num" style="background:var(--gold)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg></div>
        <h2 style="font-size:16px">Umbrales de aprobación</h2>
      </div>
      <div class="form-grid">
        <div class="field">
          <label>Endeudamiento máx. — aprobado (%)</label>
          <input type="number" name="umbral_verde" min="0" max="100" step="1" value="{{ old('umbral_verde', $params->umbral_verde) }}">
          <span class="hint">Por debajo de este % de endeudamiento, el estudio se aprueba directo.</span>
        </div>
        <div class="field">
          <label>Endeudamiento máx. — límite (%)</label>
          <input type="number" name="umbral_amarillo" min="0" max="100" step="1" value="{{ old('umbral_amarillo', $params->umbral_amarillo) }}">
          <span class="hint">Por encima de este %, el estudio se marca "No viable".</span>
        </div>
        <div class="field">
          <label>Cobertura mínima (veces la cuota)</label>
          <input type="number" name="min_cobertura" min="0" step="0.1" value="{{ old('min_cobertura', $params->min_cobertura) }}">
          <span class="hint">Cuántas veces debe alcanzar el ingreso disponible para cubrir la cuota.</span>
        </div>
      </div>
    </div>

    <div class="card pad" style="margin-bottom:20px">
      <div class="head" style="padding:0;border:none;margin-bottom:6px">
        <div class="num" style="background:var(--gold)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></div>
        <h2 style="font-size:16px">Programas académicos y matrículas</h2>
      </div>
      <p class="hint" style="margin-bottom:14px">Actualiza el valor cuando cambie la matrícula, agrega un programa nuevo, o elimina uno que ya no se ofrezca.</p>
      <div id="programasList">
        @foreach ($programas as $i => $p)
          <div class="programa-row" data-row>
            <input type="hidden" name="programas[{{ $i }}][id]" value="{{ $p->id }}">
            <select name="programas[{{ $i }}][grupo]">
              <option value="Pregrado" {{ $p->grupo === 'Pregrado' ? 'selected' : '' }}>Pregrado</option>
              <option value="Posgrado" {{ $p->grupo === 'Posgrado' ? 'selected' : '' }}>Posgrado</option>
            </select>
            <input type="text" name="programas[{{ $i }}][nombre]" value="{{ $p->nombre }}" placeholder="Nombre del programa">
            <div class="money"><input type="number" name="programas[{{ $i }}][matricula]" value="{{ $p->matricula }}" min="0"></div>
            <button type="button" class="btn-quitar" data-quitar title="Quitar programa"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button>
          </div>
        @endforeach
      </div>
      <div class="actions" style="margin-top:14px">
        <button class="btn ghost sm" id="btnAgregarPrograma" type="button">
          <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Agregar programa
        </button>
      </div>
    </div>

    <div class="actions">
      <button class="btn primary" type="submit">
        <svg class="icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg>
        Guardar parámetros
      </button>
    </div>
  </form>

  <form method="POST" action="{{ route('parametros.restaurar') }}" id="form-restaurar" style="margin-top:10px" onsubmit="return confirm('¿Restaurar los valores originales de fábrica? Esto no afecta los estudios ya guardados en el historial.');">
    @csrf
    <button class="btn ghost sm" type="submit">Restaurar valores originales</button>
  </form>
</section>
@endsection

@section('scripts')
<script>
(function () {
  'use strict';

  var list = document.getElementById('programasList');
  var btnAgregar = document.getElementById('btnAgregarPrograma');

  function reindexar() {
    list.querySelectorAll('[data-row]').forEach(function (row, idx) {
      row.querySelectorAll('[name]').forEach(function (input) {
        input.name = input.name.replace(/programas\[\d+\]/, 'programas[' + idx + ']');
      });
    });
  }

  btnAgregar.addEventListener('click', function () {
    var idx = list.querySelectorAll('[data-row]').length;
    var row = document.createElement('div');
    row.className = 'programa-row';
    row.setAttribute('data-row', '');
    row.innerHTML =
      '<input type="hidden" name="programas[' + idx + '][id]" value="">' +
      '<select name="programas[' + idx + '][grupo]">' +
        '<option value="Pregrado" selected>Pregrado</option>' +
        '<option value="Posgrado">Posgrado</option>' +
      '</select>' +
      '<input type="text" name="programas[' + idx + '][nombre]" value="" placeholder="Nombre del programa">' +
      '<div class="money"><input type="number" name="programas[' + idx + '][matricula]" value="0" min="0"></div>' +
      '<button type="button" class="btn-quitar" data-quitar title="Quitar programa"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg></button>';
    list.appendChild(row);
  });

  list.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-quitar]');
    if (!btn) return;
    var filas = list.querySelectorAll('[data-row]');
    if (filas.length <= 1) {
      alert('Debe quedar al menos un programa registrado.');
      return;
    }
    if (!confirm('¿Quitar este programa de la lista?')) return;
    btn.closest('[data-row]').remove();
    reindexar();
  });

  document.getElementById('form-parametros').addEventListener('submit', function (e) {
    var cuotaInicial = Number(document.querySelector('[name=cuota_inicial_minima_pct]').value);
    if (cuotaInicial < 0 || cuotaInicial > 100) {
      e.preventDefault();
      alert('La cuota inicial mínima debe estar entre 0% y 100%.');
      return;
    }
    var minCuotas = Number(document.querySelector('[name=min_cuotas]').value);
    var maxCuotas = Number(document.querySelector('[name=max_cuotas]').value);
    if (minCuotas < 1 || maxCuotas < minCuotas) {
      e.preventDefault();
      alert('El rango de cuotas no es válido: revisa el mínimo y el máximo.');
      return;
    }
    var umbralVerde = Number(document.querySelector('[name=umbral_verde]').value);
    var umbralAmarillo = Number(document.querySelector('[name=umbral_amarillo]').value);
    if (umbralVerde <= 0 || umbralAmarillo <= umbralVerde) {
      e.preventDefault();
      alert('El umbral "límite" debe ser mayor que el umbral "aprobado".');
      return;
    }
    var programasValidos = true;
    list.querySelectorAll('[data-row]').forEach(function (row) {
      var nombre = row.querySelector('[name*="[nombre]"]').value.trim();
      var matricula = Number(row.querySelector('[name*="[matricula]"]').value);
      if (!nombre || matricula <= 0) programasValidos = false;
    });
    if (!programasValidos) {
      e.preventDefault();
      alert('Revisa la lista de programas: todos deben tener nombre y un valor de matrícula mayor a cero.');
      return;
    }
  });
})();
</script>
@endsection
