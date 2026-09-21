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
      <div class="head" style="padding:0;border:none;margin-bottom:16px">
        <div class="num" style="background:var(--charcoal)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M4 20h16"/><path d="M4 4h16"/><path d="M4 12h16"/></svg></div>
        <h2 style="font-size:16px">Marca institucional y textos de decisión</h2>
      </div>
      <p class="hint" style="margin-bottom:14px">Estos textos aparecen en el resultado del estudio. Cámbialos si el sistema se reutiliza con otra institución o convenio.</p>
      <div class="form-grid">
        <div class="field full">
          <label>Nombre de la institución / convenio</label>
          <input type="text" name="nombre_institucion" value="{{ old('nombre_institucion', $params->nombre_institucion) }}">
        </div>
        <div class="field">
          <label>Texto — nivel aprobado</label>
          <input type="text" name="texto_verde" value="{{ old('texto_verde', $params->texto_verde) }}">
        </div>
        <div class="field">
          <label>Subtexto — nivel aprobado</label>
          <input type="text" name="subtexto_verde" value="{{ old('subtexto_verde', $params->subtexto_verde) }}">
        </div>
        <div class="field">
          <label>Texto — nivel requiere revisión</label>
          <input type="text" name="texto_amarillo" value="{{ old('texto_amarillo', $params->texto_amarillo) }}">
        </div>
        <div class="field">
          <label>Subtexto — nivel requiere revisión</label>
          <input type="text" name="subtexto_amarillo" value="{{ old('subtexto_amarillo', $params->subtexto_amarillo) }}">
        </div>
        <div class="field">
          <label>Texto — nivel no viable</label>
          <input type="text" name="texto_rojo" value="{{ old('texto_rojo', $params->texto_rojo) }}">
        </div>
        <div class="field">
          <label>Subtexto — nivel no viable</label>
          <input type="text" name="subtexto_rojo" value="{{ old('subtexto_rojo', $params->subtexto_rojo) }}">
        </div>
      </div>
    </div>

    <div class="card pad" style="margin-bottom:20px">
      <div class="head" style="padding:0;border:none;margin-bottom:16px">
        <div class="num" style="background:var(--charcoal)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18"/></svg></div>
        <h2 style="font-size:16px">Campos opcionales del formulario</h2>
      </div>
      <p class="hint" style="margin-bottom:14px">Desmarca los campos que no apliquen a este convenio. Los campos esenciales para el cálculo (nombre, programa, cuotas) siempre se muestran.</p>
      <div class="form-grid">
        @foreach (\App\Models\Parametro::CAMPOS_OPCIONALES as $campo => $etiqueta)
          <label style="display:flex;align-items:center;gap:8px;font-weight:500;font-size:13.5px;color:var(--ink-soft)">
            <input type="checkbox" name="campos_visibles[{{ $campo }}]" value="1" style="width:auto" {{ $params->campoVisible($campo) ? 'checked' : '' }}>
            {{ $etiqueta }}
          </label>
        @endforeach
      </div>
    </div>

    <div class="card pad" style="margin-bottom:20px">
      <div class="head" style="padding:0;border:none;margin-bottom:6px">
        <div class="num" style="background:var(--gold)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg></div>
        <h2 style="font-size:16px">Documentos de soporte requeridos</h2>
      </div>
      <p class="hint" style="margin-bottom:14px">Define qué documentos se piden y en qué caso son obligatorios según la actividad económica del codeudor. Marca "Siempre requerido" o selecciona las actividades que lo activan.</p>
      <div id="documentosList">
        @foreach ($documentos as $i => $d)
          <div class="doc-config-row" data-doc-row style="border:1px solid var(--line);border-radius:var(--radius-sm);padding:12px;margin-bottom:10px">
            <input type="hidden" name="documentos[{{ $i }}][id]" value="{{ $d->id }}">
            <div class="form-grid" style="margin-bottom:10px">
              <div class="field full">
                <label>Nombre del documento</label>
                <input type="text" name="documentos[{{ $i }}][nombre]" value="{{ $d->nombre }}" placeholder="Nombre del documento">
              </div>
            </div>
            <label style="display:flex;align-items:center;gap:8px;font-weight:500;font-size:13.5px;color:var(--ink-soft);margin-bottom:8px">
              <input type="checkbox" class="chk-siempre" name="documentos[{{ $i }}][siempre_requerido]" value="1" style="width:auto" {{ $d->siempre_requerido ? 'checked' : '' }}>
              Siempre requerido (para todos los casos)
            </label>
            <div class="actividades-wrap" style="display:flex;flex-wrap:wrap;gap:14px;{{ $d->siempre_requerido ? 'opacity:.4' : '' }}">
              @foreach ($actividadesDisponibles as $act)
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--ink-soft)">
                  <input type="checkbox" name="documentos[{{ $i }}][actividades][]" value="{{ $act }}" style="width:auto" {{ in_array($act, $d->actividades ?? [], true) ? 'checked' : '' }} {{ $d->siempre_requerido ? 'disabled' : '' }}>
                  {{ $act }}
                </label>
              @endforeach
            </div>
            <div style="margin-top:10px">
              <button type="button" class="btn-quitar" data-quitar-doc title="Quitar documento"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg> Quitar</button>
            </div>
          </div>
        @endforeach
      </div>
      <div class="actions" style="margin-top:14px">
        <button class="btn ghost sm" id="btnAgregarDocumento" type="button">
          <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
          Agregar documento
        </button>
      </div>
    </div>

    <div class="card pad" style="margin-bottom:20px">
      <div class="head" style="padding:0;border:none;margin-bottom:6px">
        <div class="num" style="background:var(--gold)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></div>
        <h2 style="font-size:16px">Programas académicos y matrículas</h2>
      </div>
      <p class="hint" style="margin-bottom:14px">Actualiza el valor cuando cambie la matrícula, agrega un programa nuevo, o elimina uno que ya no se ofrezca. La cuota inicial y la tasa quedan en blanco por defecto, y en ese caso el programa usa el valor global definido arriba; solo diligéncialas si este programa necesita una condición distinta.</p>
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
            <input type="number" name="programas[{{ $i }}][cuota_inicial_pct]" value="{{ $p->cuota_inicial_pct }}" min="0" max="100" placeholder="Global" title="Cuota inicial mínima (%) — vacío usa el global">
            <input type="number" name="programas[{{ $i }}][tasa_mensual]" value="{{ $p->tasa_mensual }}" min="0" step="0.1" placeholder="Global" title="Tasa mensual (%) — vacío usa el global">
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

  var ACTIVIDADES = @json($actividadesDisponibles);
  var docList = document.getElementById('documentosList');
  var btnAgregarDoc = document.getElementById('btnAgregarDocumento');

  function reindexarDocs() {
    docList.querySelectorAll('[data-doc-row]').forEach(function (row, idx) {
      row.querySelectorAll('[name]').forEach(function (input) {
        input.name = input.name.replace(/documentos\[\d+\]/, 'documentos[' + idx + ']');
      });
    });
  }

  function actividadesHtml(idx) {
    return ACTIVIDADES.map(function (act) {
      return '<label style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--ink-soft)">' +
        '<input type="checkbox" name="documentos[' + idx + '][actividades][]" value="' + act + '" style="width:auto">' + act + '</label>';
    }).join('');
  }

  btnAgregarDoc.addEventListener('click', function () {
    var idx = docList.querySelectorAll('[data-doc-row]').length;
    var row = document.createElement('div');
    row.className = 'doc-config-row';
    row.setAttribute('data-doc-row', '');
    row.style.cssText = 'border:1px solid var(--line);border-radius:var(--radius-sm);padding:12px;margin-bottom:10px';
    row.innerHTML =
      '<input type="hidden" name="documentos[' + idx + '][id]" value="">' +
      '<div class="form-grid" style="margin-bottom:10px"><div class="field full">' +
      '<label>Nombre del documento</label>' +
      '<input type="text" name="documentos[' + idx + '][nombre]" value="" placeholder="Nombre del documento"></div></div>' +
      '<label style="display:flex;align-items:center;gap:8px;font-weight:500;font-size:13.5px;color:var(--ink-soft);margin-bottom:8px">' +
      '<input type="checkbox" class="chk-siempre" name="documentos[' + idx + '][siempre_requerido]" value="1" style="width:auto">' +
      'Siempre requerido (para todos los casos)</label>' +
      '<div class="actividades-wrap" style="display:flex;flex-wrap:wrap;gap:14px">' + actividadesHtml(idx) + '</div>' +
      '<div style="margin-top:10px"><button type="button" class="btn-quitar" data-quitar-doc title="Quitar documento">' +
      '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg> Quitar</button></div>';
    docList.appendChild(row);
  });

  docList.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-quitar-doc]');
    if (!btn) return;
    var filas = docList.querySelectorAll('[data-doc-row]');
    if (filas.length <= 1) {
      alert('Debe quedar al menos un documento configurado.');
      return;
    }
    if (!confirm('¿Quitar este documento de la lista?')) return;
    btn.closest('[data-doc-row]').remove();
    reindexarDocs();
  });

  docList.addEventListener('change', function (e) {
    if (!e.target.classList.contains('chk-siempre')) return;
    var row = e.target.closest('[data-doc-row]');
    var wrap = row.querySelector('.actividades-wrap');
    var checks = wrap.querySelectorAll('input[type=checkbox]');
    wrap.style.opacity = e.target.checked ? '.4' : '1';
    checks.forEach(function (c) { c.disabled = e.target.checked; });
  });

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
      '<input type="number" name="programas[' + idx + '][cuota_inicial_pct]" value="" min="0" max="100" placeholder="Global" title="Cuota inicial mínima (%) — vacío usa el global">' +
      '<input type="number" name="programas[' + idx + '][tasa_mensual]" value="" min="0" step="0.1" placeholder="Global" title="Tasa mensual (%) — vacío usa el global">' +
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
    var documentosValidos = true;
    docList.querySelectorAll('[data-doc-row]').forEach(function (row) {
      var nombre = row.querySelector('[name*="[nombre]"]').value.trim();
      if (!nombre) documentosValidos = false;
    });
    if (!documentosValidos) {
      e.preventDefault();
      alert('Revisa la lista de documentos: todos deben tener un nombre.');
      return;
    }
  });
})();
</script>
@endsection
