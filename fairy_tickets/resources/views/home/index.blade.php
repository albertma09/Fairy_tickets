@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')
    <h2>Próximos eventos</h2>
    <ul>
        @forelse ($events as $event)
            <li>{{$event->name}}</li>
        @empty
        <li>No hay ningún evento que mostrar.</li>
        @endforelse
    </ul>

@endsection
