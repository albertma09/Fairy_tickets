@extends('layouts.master')

@section('title', 'Mi título')

@section('content')
    <h2>Contenido vario</h2>
    <ul>
        @forelse ($events as $event)
            <li>{{$event->name}}</li>
        @empty
        @endforelse
    </ul>

@endsection
