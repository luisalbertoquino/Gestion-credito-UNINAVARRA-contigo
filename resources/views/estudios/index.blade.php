@extends('layouts.creditos')

@section('title', 'Nuevo estudio')

@section('header-actions')
  <button class="btn ghost-dark sm" type="button" id="btnNuevo">
    <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>Nuevo
  </button>
  <button class="btn gold sm" type="submit" form="form-estudio">
    <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg>Guardar
  </button>
  <button class="btn ghost-dark sm" type="button" id="btnImprimir">
    <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>Imprimir / PDF
  </button>
@endsection

@section('content')
<section class="panel" id="panel-estudio">
  @if (session('status'))
    <div class="card pad" style="margin-bottom:18px;border-color:var(--green);background:var(--green-tint);color:#1d6b49">{{ session('status') }}</div>
  @endif
  @if (session('error'))
    <div class="card pad" style="margin-bottom:18px;border-color:var(--danger);background:var(--danger-tint);color:#a3352b">{{ session('error') }}</div>
  @endif
  @if ($errors->any())
    <div class="card pad" style="margin-bottom:18px;border-color:var(--danger);background:var(--danger-tint);color:#a3352b">
      <strong>Revisa los siguientes campos:</strong>
      <ul style="margin:6px 0 0;padding-left:18px">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  <form id="form-estudio" method="POST" action="{{ route('estudios.store') }}">
    @csrf
    <div class="grid-main">
      <div class="stack">

        <!-- PASO 1 -->
        <div class="card">
          <div class="head">
            <div class="num">1</div>
            <div class="titles"><p class="eyebrow">Paso 1</p><h2>Datos del estudiante (deudor)</h2></div>
          </div>
          <div class="body">
            <div class="form-grid">
              <div class="field"><label>Nombres</label><input type="text" name="est_nombre" id="est_nombre" placeholder="Nombres" value="{{ old('est_nombre') }}" required></div>
              <div class="field"><label>Apellidos</label><input type="text" name="est_apellidos" id="est_apellidos" placeholder="Apellidos" value="{{ old('est_apellidos') }}" required></div>
              <div class="field"><label>Tipo de documento</label><select name="est_tipoDoc" id="est_tipoDoc"><option>C.C.</option><option>T.I.</option><option>C.E.</option><option>Pasaporte</option></select></div>
              <div class="field"><label>Número de documento</label><input type="text" name="est_doc" id="est_doc" placeholder="Número" value="{{ old('est_doc') }}"></div>
              @if ($params->campoVisible('est_telefono'))
              <div class="field"><label>Teléfono</label><input type="tel" name="est_telefono" id="est_telefono" placeholder="Celular" value="{{ old('est_telefono') }}"></div>
              @endif
              @if ($params->campoVisible('est_correo'))
              <div class="field"><label>Correo electrónico</label><input type="email" name="est_correo" id="est_correo" placeholder="correo@ejemplo.com" value="{{ old('est_correo') }}"></div>
              @endif
              <div class="field"><label>Ingreso mensual del estudiante</label><div class="money"><input type="number" name="est_ingreso" id="est_ingreso" value="{{ old('est_ingreso', 0) }}" min="0"></div><span class="hint">Opcional — si el estudiante aporta ingresos</span></div>
            </div>
          </div>
        </div>

        <!-- PASO 2 -->
        <div class="card">
          <div class="head">
            <div class="num">2</div>
            <div class="titles"><p class="eyebrow">Paso 2</p><h2>Datos del codeudor (deudor solidario)</h2></div>
          </div>
          <div class="body">
            <div class="form-grid">
              <div class="field"><label>Nombres</label><input type="text" name="cod_nombre" id="cod_nombre" placeholder="Nombres" value="{{ old('cod_nombre') }}"></div>
              <div class="field"><label>Apellidos</label><input type="text" name="cod_apellidos" id="cod_apellidos" placeholder="Apellidos" value="{{ old('cod_apellidos') }}"></div>
              <div class="field"><label>Tipo de documento</label><select name="cod_tipoDoc" id="cod_tipoDoc"><option>C.C.</option><option>T.I.</option><option>C.E.</option><option>Pasaporte</option></select></div>
              <div class="field"><label>Número de documento</label><input type="text" name="cod_doc" id="cod_doc" placeholder="Número" value="{{ old('cod_doc') }}"></div>
              @if ($params->campoVisible('cod_relacion'))
              <div class="field"><label>Relación con el estudiante</label>
                <select name="cod_relacion" id="cod_relacion"><option>Padre/Madre</option><option>Hermano(a)</option><option>Cónyuge</option><option>Otro familiar</option><option>Otro</option></select>
              </div>
              @endif
              <div class="field"><label>Actividad económica</label>
                <select name="cod_actividad" id="cod_actividad"><option>Empleado</option><option>Independiente</option><option>Comerciante</option><option>Pensionado</option></select>
              </div>
              <div class="field"><label>Ingreso mensual</label><div class="money"><input type="number" name="cod_ingreso" id="cod_ingreso" value="{{ old('cod_ingreso', 0) }}" min="0"></div><span class="hint">Comprobable</span></div>
              <div class="field"><label>Egresos / gastos mensuales</label><div class="money"><input type="number" name="cod_egresos" id="cod_egresos" value="{{ old('cod_egresos', 0) }}" min="0"></div></div>
              @if ($params->campoVisible('cod_otras_deudas'))
              <div class="field"><label>Cuota de otras deudas</label><div class="money"><input type="number" name="cod_otrasDeudas" id="cod_otrasDeudas" value="{{ old('cod_otrasDeudas', 0) }}" min="0"></div><span class="hint">Créditos vigentes</span></div>
              @endif
            </div>
          </div>
        </div>

        <!-- PASO 3 -->
        <div class="card">
          <div class="head">
            <div class="num">3</div>
            <div class="titles"><p class="eyebrow">Paso 3</p><h2>Configuración del crédito</h2></div>
            <span class="tag" id="tagCuotas">{{ $params->min_cuotas }} cuotas</span>
          </div>
          <div class="body">
            <div class="form-grid">
              <div class="field full">
                <label>Programa académico</label>
                <select name="programa_id" id="programa">
                  @foreach (['Pregrado', 'Posgrado'] as $grupo)
                    <optgroup label="{{ $grupo }}">
                      @foreach ($programas->where('grupo', $grupo) as $p)
                        <option value="{{ $p->id }}" data-matricula="{{ $p->matricula }}" data-cuota-inicial="{{ $p->cuotaInicialPct($params) }}" data-tasa="{{ $p->tasaMensual($params) }}">{{ $p->nombre }} — $ {{ number_format($p->matricula, 0, ',', '.') }}</option>
                      @endforeach
                    </optgroup>
                  @endforeach
                </select>
              </div>
              @php($cuotaInicialInicial = ($programas->first() ?? null)?->cuotaInicialPct($params) ?? $params->cuota_inicial_minima_pct)
              <div class="field">
                <label>Cuota inicial (%)</label>
                <input type="number" name="cuotaInicialPct" id="cuotaInicialPct" value="{{ old('cuota_inicial_pct', $cuotaInicialInicial) }}" min="{{ $cuotaInicialInicial }}" max="100" step="1">
                <span class="error-text" id="err_cuotaInicial">La cuota inicial no puede ser menor al mínimo permitido.</span>
                <span class="hint" id="hint_cuotaInicial">Mínimo permitido: {{ $cuotaInicialInicial }}%</span>
              </div>
              <div class="field">
                <label>Número de cuotas</label>
                <input type="number" name="numCuotas" id="numCuotas" value="{{ old('n_cuotas', min(5, $params->max_cuotas)) }}" min="{{ $params->min_cuotas }}" max="{{ $params->max_cuotas }}" step="1">
                <span class="error-text" id="err_numCuotas">El número de cuotas está fuera del rango permitido.</span>
                <span class="hint" id="hint_numCuotas">Rango permitido: {{ $params->min_cuotas }} a {{ $params->max_cuotas }} cuotas</span>
              </div>
              <div class="field"><label>Fecha de matrícula (cuota inicial)</label><input type="date" name="fechaMatricula" id="fechaMatricula" value="{{ old('fecha_matricula', now()->toDateString()) }}"></div>
              @if ($params->campoVisible('fecha_primera_cuota'))
              <div class="field"><label>Fecha de la primera cuota</label><input type="date" name="fechaPrimeraCuota" id="fechaPrimeraCuota" value="{{ old('fecha_primera_cuota', now()->toDateString()) }}"></div>
              @endif
            </div>
          </div>
        </div>

        <!-- PASO 4: DOCUMENTOS (enlace, no archivo) -->
        <div class="card">
          <div class="head">
            <div class="num">4</div>
            <div class="titles"><p class="eyebrow">Paso 4</p><h2>Documentos de soporte</h2></div>
            <span class="tag" id="tagDocs">0/2 requeridos</span>
          </div>
          <div class="body">
            <p class="hint" style="margin-bottom:14px">Los documentos requeridos se ajustan a la actividad económica del codeudor (<strong id="txtActividad">Empleado</strong>). Pega el enlace donde ya tengas cada documento alojado (Google Drive, OneDrive, etc.) — no se suben archivos a este sistema.</p>
            <div id="docsList">
              @foreach ($documentos as $doc)
                <div class="doc-row" data-doc-row="{{ $doc->clave }}">
                  <div class="info">
                    <div class="name">{{ $doc->nombre }}</div>
                    <div class="req-or-opt-label"></div>
                  </div>
                  <div style="flex:2;display:flex;align-items:center;gap:8px">
                    <input type="url" data-doc="{{ $doc->clave }}" name="documentos[{{ $doc->clave }}]" placeholder="https://drive.google.com/... o cualquier enlace">
                    <span class="link-ok" style="display:none"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>Enlace guardado</span>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <!-- PASO 5: PLAN DE PAGOS -->
        <div class="card">
          <div class="head">
            <div class="num">5</div>
            <div class="titles"><p class="eyebrow">Paso 5</p><h2>Plan de pagos</h2></div>
          </div>
          <div class="body" style="padding:0">
            <div style="overflow-x:auto">
              <table class="plan">
                <thead><tr><th>Concepto</th><th>Fecha</th><th class="num">Valor cuota</th><th class="num">Saldo</th></tr></thead>
                <tbody id="planBody"></tbody>
              </table>
            </div>
          </div>
        </div>

      </div>

      <!-- ============ PANEL LATERAL ============ -->
      <div class="stack">
        <div class="card pad" id="paramsBlockedNotice" style="display:none;border-color:#f0c4c8;background:var(--danger-tint)">
          <div style="display:flex;gap:10px;align-items:flex-start">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--danger)" stroke-width="2" style="flex:none;margin-top:1px"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            <div>
              <div style="font-weight:700;color:var(--danger);font-size:13.5px;margin-bottom:3px">Corrige los parámetros del crédito</div>
              <div style="font-size:12.5px;color:var(--ink-soft)" id="paramsBlockedText">La cuota inicial debe ser de al menos {{ $params->cuota_inicial_minima_pct }}%, y el número de cuotas debe estar entre {{ $params->min_cuotas }} y {{ $params->max_cuotas }}, para poder calcular el estudio.</div>
            </div>
          </div>
        </div>

        <div id="resultsBlock">
        <div class="decision ROJO" id="decisionBox">
          <p class="eyebrow"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>Decisión del estudio</p>
          <div class="txt" id="decisionTxt">—</div>
          <div class="sub" id="decisionSub">Completa el formulario para calcular</div>
        </div>

        <div class="kpi-grid">
          <div class="kpi warn"><div class="label">Ingreso disponible</div><div class="val" id="kpiDisponible">$ 0</div></div>
          <div class="kpi"><div class="label">Nivel de endeudamiento</div><div class="val" id="kpiEndeudamiento">—</div></div>
          <div class="kpi warn"><div class="label">Cuota mensual</div><div class="val" id="kpiCuota">$ 0</div></div>
          <div class="kpi"><div class="label">Cobertura</div><div class="val" id="kpiCobertura">0.0×</div></div>
        </div>

        <div class="card pad">
          <div style="display:flex;justify-content:space-between;align-items:center;font-size:11px;letter-spacing:.05em;text-transform:uppercase;color:var(--muted);font-weight:600">
            <span>Documentación del expediente</span><span id="docsProgTxt">0/2</span>
          </div>
          <div class="progress-bar"><div class="fill" id="docsProgFill" style="width:0%"></div></div>
        </div>

        <div class="fundamento ROJO" id="fundamentoBox">
          <div class="t"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>Fundamento</div>
          <ul id="fundamentoList"></ul>
        </div>

        <div class="card pad">
          <div class="head" style="padding:0;border:none;margin-bottom:14px">
            <div class="num" style="background:var(--gold)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2"/><line x1="8" x2="16" y1="6" y2="6"/><line x1="16" x2="16" y1="14" y2="18"/><path d="M16 10h.01"/><path d="M12 10h.01"/><path d="M8 10h.01"/><path d="M12 14h.01"/><path d="M8 14h.01"/><path d="M12 18h.01"/><path d="M8 18h.01"/></svg></div>
            <h2 style="font-size:15px">Resumen económico</h2>
          </div>
          <div class="resumen-header">
            <div class="label" id="resProgramaGrupo">Valor de la matrícula</div>
            <div class="val" id="resValorMatricula">$ 0</div>
            <div class="prog" id="resProgramaNombre">—</div>
          </div>
          <div class="resumen-row"><span>Cuota inicial (<span id="resPct">{{ $params->cuota_inicial_minima_pct }}</span>%)</span><span class="n" id="resCuotaInicial">$ 0</span></div>
          <div class="resumen-row"><span>Saldo a financiar</span><span class="n" id="resSaldo">$ 0</span></div>
          <div class="resumen-row total"><span>Valor de cada cuota (<span id="resNumCuotas">0</span>)</span><span class="n" id="resValorCuota">$ 0</span></div>
        </div>
        </div>
      </div>
    </div>
  </form>
</section>
@endsection

@section('scripts')
<script>
(function () {
  'use strict';

  function money(n) {
    return '$ ' + Math.round(n || 0).toLocaleString('es-CO');
  }
  function setText(id, txt) { var el = document.getElementById(id); if (el) el.textContent = txt; }
  function formatDate(iso) {
    var d = new Date(iso + 'T00:00:00');
    return d.toLocaleDateString('es-CO', { day: '2-digit', month: 'long', year: 'numeric' });
  }

  var form = document.getElementById('form-estudio');
  var csrf = document.querySelector('meta[name="csrf-token"]');
  var calcularUrl = @json(route('estudios.calcular'));

  var camposRecalculo = [
    'est_ingreso', 'cod_ingreso', 'cod_egresos', 'cod_otrasDeudas', 'cod_actividad',
    'programa', 'cuotaInicialPct', 'numCuotas', 'fechaMatricula', 'fechaPrimeraCuota'
  ];

  var debounceTimer = null;
  function agendarRecalculo() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(recalcular, 200);
  }

  camposRecalculo.forEach(function (id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.addEventListener('input', agendarRecalculo);
    el.addEventListener('change', agendarRecalculo);
  });

  document.querySelectorAll('#docsList input[data-doc]').forEach(function (input) {
    input.addEventListener('input', function () {
      var row = input.closest('.doc-row');
      var ok = /^https?:\/\/.+/.test(input.value.trim());
      var span = row.querySelector('.link-ok');
      if (span) span.style.display = ok ? 'flex' : 'none';
      agendarRecalculo();
    });
  });

  function actividadActual() {
    return document.getElementById('cod_actividad').value;
  }

  function actualizarHintCuotaInicial() {
    var select = document.getElementById('programa');
    var option = select.options[select.selectedIndex];
    if (!option) return;
    var minimo = option.dataset.cuotaInicial || '0';
    var pctInput = document.getElementById('cuotaInicialPct');
    pctInput.min = minimo;
    setText('hint_cuotaInicial', 'Mínimo permitido: ' + minimo + '%');
    if (Number(pctInput.value) < Number(minimo)) {
      pctInput.value = minimo;
    }
  }

  document.getElementById('programa').addEventListener('change', actualizarHintCuotaInicial);

  var REGLAS_DOCUMENTOS = @json($documentos->map(fn ($d) => ['clave' => $d->clave, 'siempre' => $d->siempre_requerido, 'actividades' => $d->actividades ?? []]));

  function actualizarDocsLabels() {
    var actividad = actividadActual();
    setText('txtActividad', actividad);
    var req = {};
    REGLAS_DOCUMENTOS.forEach(function (d) {
      req[d.clave] = d.siempre || d.actividades.indexOf(actividad) !== -1;
    });
    document.querySelectorAll('#docsList .doc-row').forEach(function (row) {
      var id = row.getAttribute('data-doc-row');
      var esReq = !!req[id];
      row.classList.toggle('required', esReq);
      var label = row.querySelector('.req-or-opt-label');
      label.textContent = esReq ? 'Requerido' : 'Opcional';
      label.classList.toggle('req-label', esReq);
      label.classList.toggle('opt-label', !esReq);
    });
  }

  function valorDe(id, porDefecto) {
    var el = document.getElementById(id);
    return el ? el.value : (porDefecto !== undefined ? porDefecto : '');
  }

  function datosFormulario() {
    var documentos = {};
    document.querySelectorAll('#docsList input[data-doc]').forEach(function (input) {
      documentos[input.dataset.doc] = input.value.trim();
    });
    return {
      programa_id: document.getElementById('programa').value,
      est_ingreso: document.getElementById('est_ingreso').value,
      cod_ingreso: document.getElementById('cod_ingreso').value,
      cod_egresos: document.getElementById('cod_egresos').value,
      cod_otrasDeudas: valorDe('cod_otrasDeudas', 0),
      cod_actividad: actividadActual(),
      cuotaInicialPct: document.getElementById('cuotaInicialPct').value,
      numCuotas: document.getElementById('numCuotas').value,
      fechaMatricula: document.getElementById('fechaMatricula').value,
      fechaPrimeraCuota: valorDe('fechaPrimeraCuota', ''),
      documentos: documentos
    };
  }

  function recalcular() {
    actualizarDocsLabels();

    fetch(calcularUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrf ? csrf.content : ''
      },
      body: JSON.stringify(datosFormulario())
    })
      .then(function (r) { return r.json(); })
      .then(pintar)
      .catch(function () {});
  }

  function pintar(data) {
    var pctInput = document.getElementById('cuotaInicialPct');
    var cuotasInput = document.getElementById('numCuotas');
    var errPct = document.getElementById('err_cuotaInicial');
    var errCuotas = document.getElementById('err_numCuotas');

    pctInput.classList.toggle('invalid', !data.pctValido);
    errPct.classList.toggle('show', !data.pctValido);
    cuotasInput.classList.toggle('invalid', !data.cuotasValido);
    errCuotas.classList.toggle('show', !data.cuotasValido);

    // Documentos (siempre se pueden pintar, sin depender de validez de params)
    if (data.docsInfo) {
      var d = data.docsInfo;
      setText('tagDocs', d.completos + '/' + d.requeridos + ' requeridos');
      setText('docsProgTxt', d.completos + '/' + d.requeridos);
      var pct = d.requeridos ? (d.completos / d.requeridos * 100) : 100;
      var fill = document.getElementById('docsProgFill');
      if (fill) fill.style.width = pct + '%';
    }

    var resultsBlock = document.getElementById('resultsBlock');
    var blockedNotice = document.getElementById('paramsBlockedNotice');

    if (!data.valido) {
      resultsBlock.style.display = 'none';
      blockedNotice.style.display = 'block';
      setText('tagCuotas', '— cuotas');
      document.getElementById('planBody').innerHTML = '';
      return;
    }
    resultsBlock.style.display = '';
    blockedNotice.style.display = 'none';

    setText('tagCuotas', data.resumen.nCuotas + ' cuota' + (data.resumen.nCuotas === 1 ? '' : 's'));

    var box = document.getElementById('decisionBox');
    box.className = 'decision ' + data.decision;
    setText('decisionTxt', data.decisionTxt);
    setText('decisionSub', data.decisionSub);

    setText('kpiDisponible', money(data.kpis.disponible));
    setText('kpiEndeudamiento', data.kpis.endeudamiento !== null ? data.kpis.endeudamiento.toFixed(1) + '%' : '—');
    setText('kpiCuota', money(data.kpis.cuota));
    setText('kpiCobertura', data.kpis.cobertura.toFixed(1) + '×');

    var fbox = document.getElementById('fundamentoBox');
    fbox.className = 'fundamento ' + data.decision;
    var flist = document.getElementById('fundamentoList');
    flist.innerHTML = '';
    data.razones.forEach(function (r) {
      var li = document.createElement('li');
      li.textContent = r;
      flist.appendChild(li);
    });

    setText('resProgramaGrupo', 'Valor de la matrícula · ' + data.programa.grupo);
    setText('resValorMatricula', money(data.resumen.matricula));
    setText('resProgramaNombre', data.programa.nombre);
    setText('resPct', data.resumen.pct);
    setText('resCuotaInicial', money(data.resumen.cuotaInicial));
    setText('resSaldo', money(data.resumen.saldo));
    setText('resNumCuotas', data.resumen.nCuotas);
    setText('resValorCuota', money(data.resumen.cuota));

    var body = document.getElementById('planBody');
    body.innerHTML = '';
    var trInicial = document.createElement('tr');
    trInicial.className = 'inicial';
    trInicial.innerHTML = '<td>• Cuota inicial</td><td>' + formatDate(data.plan.fechaInicial) + '</td><td class="num">' + money(data.plan.cuotaInicial) + '</td><td class="num">' + money(data.plan.saldoInicial) + '</td>';
    body.appendChild(trInicial);

    data.plan.filas.forEach(function (fila) {
      var tr = document.createElement('tr');
      tr.innerHTML = '<td>Cuota ' + fila.n + ' de ' + fila.nCuotas + '</td><td>' + formatDate(fila.fecha) + '</td><td class="num">' + money(fila.cuota) + '</td><td class="num">' + money(fila.saldo) + '</td>';
      body.appendChild(tr);
    });

    var trTotal = document.createElement('tr');
    trTotal.className = 'total';
    trTotal.innerHTML = '<td>Total del crédito</td><td></td><td class="num">' + money(data.plan.totalCredito) + '</td><td class="num">$ 0</td>';
    body.appendChild(trTotal);
  }

  document.getElementById('btnNuevo').addEventListener('click', function () {
    if (!confirm('¿Limpiar el formulario para iniciar un nuevo estudio? Los datos no guardados se perderán.')) return;
    window.location.href = window.location.pathname;
  });

  document.getElementById('btnImprimir').addEventListener('click', function () { window.print(); });

  form.addEventListener('submit', function (e) {
    if (!document.getElementById('resultsBlock') || document.getElementById('resultsBlock').style.display === 'none') {
      e.preventDefault();
      alert('Corrige la cuota inicial o el número de cuotas antes de guardar: están fuera del rango permitido.');
    }
  });

  recalcular();
})();
</script>
@endsection
