@extends('layouts.creditos')

@section('title', 'Usuarios')

@section('content')
<section class="panel" id="panel-usuarios">
  <p class="eyebrow-lg"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Configuración</p>
  <h2 class="sec" style="font-family:var(--serif);font-size:24px;margin-bottom:6px">Usuarios del sistema</h2>
  <p class="lede">El administrador configura los parámetros del crédito y gestiona usuarios. El profesional financiero solo registra y consulta estudios de crédito.</p>

  <div class="card pad" style="margin-bottom:20px">
    <div class="head" style="padding:0;border:none;margin-bottom:16px">
      <div class="num"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M5 12h14"/><path d="M12 5v14"/></svg></div>
      <h2 style="font-size:16px">Crear usuario</h2>
    </div>
    <form method="POST" action="{{ route('usuarios.store') }}">
      @csrf
      <div class="form-grid">
        <div class="field">
          <label>Nombre completo</label>
          <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="field">
          <label>Correo electrónico</label>
          <input type="email" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="field">
          <label>Contraseña</label>
          <input type="password" name="password" minlength="8" required>
          <span class="hint">Mínimo 8 caracteres.</span>
        </div>
        <div class="field">
          <label>Rol</label>
          <select name="role" required>
            <option value="financiera" {{ old('role') === 'financiera' ? 'selected' : '' }}>Profesional financiera</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
          </select>
        </div>
      </div>
      <div class="actions" style="margin-top:14px">
        <button class="btn primary" type="submit">Crear usuario</button>
      </div>
    </form>
  </div>

  <div class="card pad">
    <div class="head" style="padding:0;border:none;margin-bottom:16px">
      <div class="num" style="background:var(--charcoal)"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M4 20h16"/><path d="M4 4h16"/><path d="M4 12h16"/></svg></div>
      <h2 style="font-size:16px">Usuarios existentes</h2>
    </div>
    <div style="overflow-x:auto">
      <table class="plan">
        <thead><tr><th>Nombre</th><th>Correo</th><th>Rol</th><th>Nueva contraseña</th><th></th></tr></thead>
        <tbody>
          @foreach ($usuarios as $u)
            <tr>
              <td><input type="text" name="name" form="edit-user-{{ $u->id }}" value="{{ $u->name }}" style="font-size:13px;padding:6px 8px"></td>
              <td><input type="email" name="email" form="edit-user-{{ $u->id }}" value="{{ $u->email }}" style="font-size:13px;padding:6px 8px"></td>
              <td>
                <select name="role" form="edit-user-{{ $u->id }}" style="font-size:13px;padding:6px 8px">
                  <option value="financiera" {{ $u->role === 'financiera' ? 'selected' : '' }}>Financiera</option>
                  <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Administrador</option>
                </select>
              </td>
              <td><input type="password" name="password" form="edit-user-{{ $u->id }}" placeholder="Dejar en blanco para no cambiar" minlength="8" style="font-size:13px;padding:6px 8px"></td>
              <td style="white-space:nowrap;display:flex;gap:6px">
                <form id="edit-user-{{ $u->id }}" method="POST" action="{{ route('usuarios.update', $u) }}">
                  @csrf
                  @method('PUT')
                </form>
                <button class="btn ghost sm" type="submit" form="edit-user-{{ $u->id }}">Guardar</button>

                <form id="delete-user-{{ $u->id }}" method="POST" action="{{ route('usuarios.destroy', $u) }}" onsubmit="return confirm('¿Eliminar este usuario?');">
                  @csrf
                  @method('DELETE')
                </form>
                <button class="btn ghost sm" type="submit" form="delete-user-{{ $u->id }}" style="color:var(--danger)" {{ $u->id === auth()->id() ? 'disabled' : '' }}>Eliminar</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
