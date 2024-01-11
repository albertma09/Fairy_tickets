@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')

<h2>Bienvenido/a, {{ auth()->user()->name }}.</h2>
<h3>Esta es tu sección privada de Fairy Tickets, aquí podrás ver y gestionar todos tus eventos.</h3>
<div><a class="button-brand" href="{{ route('events.create') }}">Nuevo Evento</a></div>
<div></div>
@endsection
