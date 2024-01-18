@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')




    <h2>Bienvenido/a, {{ auth()->user()->name }}.</h2>
    <h3>Esta es tu sección privada de Fairy Tickets, aquí podrás ver y gestionar todos tus eventos.</h3>
    
    <div>
       <div class="add-event"><a class="button button-brand" href="{{ route('events.create') }}">Añadir evento  <i class='fas fa-plus'></i></a></div>
        <div class="promotor-container">
            @foreach ($events as $event)
                <div class="event-card">
                    <div class="img">
                        <img
            src="https://images.unsplash.com/photo-1580501170961-bb0dbf63a6df?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2970&q=80" />
        
                    </div>
                    <h3>{{ $event->name }}</h3>
                    <!-- Otros detalles del evento si es necesario -->
                </div>
            @endforeach
        </div>
    </div>
@endsection
