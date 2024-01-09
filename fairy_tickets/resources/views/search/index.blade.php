@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')
    <h2>Próximos eventos</h2>


    @forelse ($events as $event)
        <x-events-component :event="$event" />
        {{-- <div>{{$event->name}}</div>
    <div>{{$event->date}}</div>
    <div>{{$event->price}} €</div> --}}
    @empty
        <li>No hay ningún evento que mostrar.</li>
    @endforelse

    {{-- <ul>
        @forelse ($events as $event)
            <li>{{$event->name}}</li>
        @empty
        <li>No hay ningún evento que mostrar.</li>
        @endforelse
    </ul> --}}

@endsection
