<!DOCTYPE html>
<html lang="es">
@extends('auth/session-main')

@section('title', 'Actualizar Contraseña')

@section('content')
    <form action="{{ route('actualizar-contrasenia') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <input type="hidden" id="email_address" value="{{ $email }}" name="email" required>




        <label for="password">Contraseña</label>

        <input type="password" id="password" name="password" required autofocus>




        <label for="password-confirm">Confirmar Contraseña</label>

        <input type="password" id="password-confirm" name="password_confirmation" required autofocus>
        @if ($errors->has('password'))
            <p class="msg-error">{{ $errors->first('password') }}</p>
        @endif
        @if ($errors->has('password_confirmation'))
            <p class="msg-error">{{ $errors->first('password_confirmation') }}</p>
        @endif



        <button type="submit" class="button button-brand">
            Cambiar Contraseña
        </button>

    </form>
@endsection
