@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')




    <h2>Bienvenido/a, {{ auth()->user()->name }}.</h2>
    <h3>Mis sesiones abiertas.</h3>

    <div>
        <div class="promotor-container">
            @if (Session::has('message'))
            <div class="alert alert-success">
                <p class="msg-correct">{{ Session::get('message') }}</p>
            </div>
            @endif
            

            @if (Session::has('error'))
            <div class="alert alert-danger">
                <p class="msg-error">{{ Session::get('error') }}</p>
            </div>
            @endif
            @foreach ($sessions as $session)
                <x-session-component :session="$session" />
            @endforeach
        </div>
    </div>
@endsection
