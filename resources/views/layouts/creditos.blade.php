<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Estudio de Crédito') · UNINAVARRA Contigo</title>
@vite(['resources/css/creditos.css'])
</head>
<body>

<header class="top">
  <div class="wrap">
    <div class="row">
      <div class="brand-shield">UN</div>
      <div>
        <h1>Estudio de Crédito · UNINAVARRA Contigo</h1>
        <div class="sub">Fundación Universitaria Navarra · Plan de financiación de matrícula 2026</div>
      </div>
      <div class="actions">
        @yield('header-actions')
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn ghost-dark sm">
            <svg class="icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
            {{ auth()->user()->name }}
          </button>
        </form>
      </div>
    </div>
    <nav class="tabs">
      <a href="{{ route('estudios.index') }}" class="{{ request()->routeIs('estudios.index') ? 'active' : '' }}">
        <svg class="icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6"/><path d="M9 16h6"/><rect width="18" height="18" x="3" y="3" rx="2"/></svg>Nuevo estudio
      </a>
      <a href="{{ route('estudios.historial') }}" class="{{ request()->routeIs('estudios.historial') ? 'active' : '' }}">
        <svg class="icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v5h5"/><path d="M3.05 13A9 9 0 1 0 6 5.3L3 8"/><path d="M12 7v5l4 2"/></svg>Historial de estudios
      </a>
      <a href="{{ route('parametros.edit') }}" class="{{ request()->routeIs('parametros.edit') ? 'active' : '' }}">
        <svg class="icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2Z"/><circle cx="12" cy="12" r="3"/></svg>Parámetros
      </a>
    </nav>
  </div>
</header>

<div class="wrap">
  @yield('content')
</div>

@if (session('status'))
<div class="toast show" id="toast">{{ session('status') }}</div>
<script>setTimeout(()=>document.getElementById('toast')?.classList.remove('show'),3200);</script>
@endif

@if (session('error'))
<div class="toast error show" id="toastError">{{ session('error') }}</div>
<script>setTimeout(()=>document.getElementById('toastError')?.classList.remove('show'),3600);</script>
@endif

@yield('scripts')
</body>
</html>
