@extends('auth/session-main')

@section('title', 'Iniciar Sesión')

@section('content')
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email" placeholder="Correo" required>
        <br>
        <input type="password" name="password" placeholder="Contraseña" required>
        <br>
        <button class="button-brand" type="submit">Iniciar Sesión</button>
        @if (session('error'))
            <p class="msg-error">{{ session('error') }}</p>
        @endif
    </form>
    <div class="footer">
        <a href="{{ route('formulario-recuperar-contrasenia') }}">¿Olvidaste la contraseña?</a>
    </div>

@endsection
