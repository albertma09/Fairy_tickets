@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')




    <h2>Bienvenido/a, {{ auth()->user()->name }}.</h2>
    <h3>Mis sesiones abiertas.</h3>

    <div>
        <div class="promotor-container">
            @foreach ($sessions as $session)
                <x-session-component :session="$session" />
            @endforeach
        </div>
    </div>
@endsection
