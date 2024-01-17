@extends('layouts.mostrar-evento')

@section('title', $evento[$id]['name'])

@section('content')

    {{-- @dd($evento) --}}


    <div class="slider-container">
        <button class="prev-btn">&#10094;</button>
        <img class="slider-item"
            src="https://images.unsplash.com/photo-1580501170961-bb0dbf63a6df?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2970&q=80" />
        <img class="slider-item"
            src="https://images.unsplash.com/photo-1580501170888-80668882ca0c?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2940&q=80" />
        <img class="slider-item"
            src="https://images.unsplash.com/photo-1572508589584-94d778209dd9?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2940&q=80" />
        <button class="next-btn">&#10095;</button>
    </div>
    <div class="container">
        <h2 class="titulo-brand">{{ $evento[$id]['name'] }}</h2>
        <p>{{ $evento[$id]['description'] }}</p>
    </div>

    <div class="container">
        <h1 class="titulo-brand">
            Sesiones
        </h1>
        <div class="sesiones-container">
            @foreach ($sessionPrices as $sessionId => $session)
                <div class="sesion-card">
                    <p class="sesion-info">Fecha de sesión: {{ $session['date'] }}</p>
                    <p class="sesion-info">Hora de sesión: {{ $session['hour'] }}</p>
                    <p class="sesion-info">Precio: {{ $session['min_price'] }}</p>
                    <button class="button button-brand">Comprar</button>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container">
        @foreach ($evento as $event)

            <p>Ubicación: {{ $event['location_name'] }}, {{ $event['street'] }}, {{ $event['number'] }},
                {{ $event['cp'] }}, {{ $event['city'] }}, {{ $event['province'] }}</p>

            
        @endforeach
    </div>


@endsection
