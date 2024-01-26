@extends('layouts.mostrar-evento')

@section('title', $evento[$id]['name'])

@section('content')

    {{-- @dd($evento) --}}
    {{-- @dd($sessionPrices) --}}
    {{-- @dd($tickets) --}}

    <div class="slider-container">
        @foreach ($evento as $event)
        <img class="slider-item"
        src="{{ asset('storage/img/covers/' . $event['image']) }}" />
        @endforeach

        <img class="slider-item"
            src="https://images.unsplash.com/photo-1580501170961-bb0dbf63a6df?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2970&q=80" />
        <img class="slider-item"
            src="https://images.unsplash.com/photo-1580501170888-80668882ca0c?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2940&q=80" />
        <img class="slider-item"
            src="https://images.unsplash.com/photo-1572508589584-94d778209dd9?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2940&q=80" />

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
                    <p class="sesion-info">Fecha de sesión: {{ $session['date'] }}
                    </p>
                    <p class="sesion-info">Hora de sesión:
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $session['hour'])->format('H:i') }}</p>
                    <p class="sesion-info">Precio: {{ $session['min_price'] }}€</p>
                    <button class="button button-brand" id="{{ $session['id'] }}">Comprar</button>
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


    <!-- Contenedor del menú emergente -->
    <div class="popup-container">
        <div class="popup-content">
            <span class="close-popup">&times;</span>
            <h1>Tickets</h1>
            <div id="ticket-types-container"></div>
            <div id="final-price">
                Total: 0.00€
            </div>
            <!-- El contenido del menú se agregará aquí dinámicamente -->
        </div>
    </div>


    <script>
        const tickets = {!! json_encode($tickets) !!};
    </script>
@endsection
