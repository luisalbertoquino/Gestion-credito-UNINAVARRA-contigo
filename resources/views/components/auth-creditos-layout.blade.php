<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $nombreInstitucion ?? 'UNINAVARRA Contigo' }} · Estudio de Crédito</title>
@vite(['resources/css/creditos.css'])
</head>
<body class="auth-body">

<div class="auth-wrap">
  <div class="auth-shield">
    <img src="{{ asset('images/escudo-bandera.webp') }}" alt="{{ $nombreInstitucion ?? 'UNINAVARRA Contigo' }}">
  </div>
  <h1 class="auth-title">Estudio de Crédito</h1>
  <div class="auth-sub">{{ $nombreInstitucion ?? 'UNINAVARRA Contigo' }}</div>

  <div class="card pad auth-card">
    {{ $slot }}
  </div>
</div>

</body>
</html>
