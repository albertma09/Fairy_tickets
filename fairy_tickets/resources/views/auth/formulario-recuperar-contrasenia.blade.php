@extends('auth/session-main')

@section('title', 'Recuperar Contraseña')

@section('content')


    <form action="{{ route('enviar-recuperacion') }}" method="POST">
        @csrf
        <input type="text" id="email_address" name="email" placeholder="email" required>
        @if ($errors->has('email'))
            <p class="msg-error">{{ $errors->first('email') }}</p>
        @endif
        @if (Session::has('message'))
            <p class="msg-correct">{{ Session::get('message') }}</p>
        @endif
        <button type="submit" class="button button-brand">
            Recuperar contraseña
        </button>

    </form>

@endsection
