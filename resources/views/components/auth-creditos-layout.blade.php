<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $nombreInstitucion ?? 'UNINAVARRA Contigo' }} · Estudio de Crédito</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
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
