@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')
    <h2>Próximos eventos</h2>


    @forelse ($events as $event)
        <x-events-component :event="$event" />
    @empty
        <div>No hay eventos</div>
    @endforelse


@endsection
