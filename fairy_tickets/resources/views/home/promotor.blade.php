@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')


    home para promotores
    @auth
        <!-- Si el usuario está autenticado, muestra un enlace para cerrar sesión -->
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Cerrar Sesión
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    @endauth
@endsection
