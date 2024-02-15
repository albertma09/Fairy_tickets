@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')

 


    <h2>Bienvenido/a, {{ auth()->user()->name }}.</h2>
    <h3>Esta es tu sección privada de Fairy Tickets, aquí podrás ver y gestionar todos tus eventos.</h3>

    <div>
        <div class="add-event"><a class="button button-brand" href="{{ route('events.create') }}">Añadir evento <i
                    class='fas fa-plus'></i></a></div>
        <div class="add-event"><a class="button button-brand"
                href="{{ route('sessions.mostrar', ['id' => auth()->user()->id]) }}">Mis sesiones </a></div>
        <div class="promotor-container">
            @foreach ($events as $event)
                <div class="event-card">
                    <div class="img">
                        <img src="{{ asset('storage/img/covers/' . $event->image) }}" />
                    </div>

                    <h3>{{ $event->name }}</h3>
                    <div class="action-container">
                        <a href="{{ route('events.update' , ['id' => $event->id]) }}" class="button button-icon">
                            <i class="fas fa-pencil-alt "></i>
                        </a>
                        <a href="{{ route('events.mostrar', ['id' => $event->id]) }}" class="button button-icon">
                            <i class="fas fa-info"></i>
                        </a>
                       
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @if (Session::has('success'))
        <x-feedback-dialog-component />
    @endif
@endsection
