@extends('layouts.mostrar-evento')

@section('title', $evento['name'])

@section('content')

    {{-- @dd($evento) --}}
    {{-- @dd($sessionPrices) --}}
    {{-- @dd($tickets) --}}

    <div class="slider-container">
        @if ($imagenes && !empty($imagenes))
            @foreach ($imagenes as $imagen)
                <img class="slider-item"
                    srcset="{{ $imagen['small'] }} 300w,
                                {{ $imagen['medium'] }} 700w,
                                {{ $imagen['big'] }} 1600w"
                    sizes="max-width: 767px 300px,
                                (min-width: 768px)
and (max-width: 1023px) 100vw,
                                min-width: 1024px 100vw"
                    alt="Imagen del evento" />
            @endforeach
        @else
            <p>No se han encontrado imágenes para este evento.</p>
        @endif

    </div>
    <div class="container">
        <h2 class="titulo-brand">{{ $evento['name'] }}</h2>
        <p>{{ $evento['description'] }}</p>
    </div>

    <div class="container">
        <h1 class="titulo-brand">
            Sesiones
        </h1>
        <a class="button button-brand" href="{{ route('sessions.create', ['eventId' => $id]) }}">Añadir Nueva
            Sesión</a>
        <div class="sesiones-container">
            @foreach ($sessionPrices as $sessionId => $session)
                <div class="sesion-card">
                    <p class="sesion-info">Fecha de sesión: {{ $session['date'] }}
                    </p>
                    <p class="sesion-info">Hora de sesión:
                        {{ \Carbon\Carbon::createFromFormat('H:i:s', $session['hour'])->format('H:i') }}</p>
                    <p class="sesion-info">Precio: {{ $session['min_price'] }}€</p>
                    <button class="button button-brand" id="{{ $session['id'] }}" name="session-buy">Comprar</button>
                </div>
            @endforeach
        </div>
    </div>

    <div class="container">
        <p>Ubicación: {{ $ubicacion->name }}, {{ $ubicacion->street }}, {{ $ubicacion->number }},
            {{ $ubicacion->cp }}, {{ $ubicacion->city }}, {{ $ubicacion->province }}</p>
        <iframe width="600" height="450" frameborder="0" style="border:0"
            src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBMNGMRDRS3lh4Q9Iug9RE6Jy326FkicHY&q={{ $ubicacion->name }}, {{ $ubicacion->street }}, {{ $ubicacion->number }},
            {{ $ubicacion->cp }}, {{ $ubicacion->city }}, {{ $ubicacion->province }}">
        </iframe>
    </div>

    <div class="container">
        <h3 class="title-section">Opiniones</h3>
        @foreach ($opinions as $opinion)
            <x-opinion-card-component :opinion="$opinion" />
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
            <div id="confirmPayButton" class="confirmPayButton">
                <form action="{{ route('payment.index') }}" method="POST">
                    @csrf
                    <input type="hidden" id="totalPrice" name="totalPrice" value="0">
                    <input type="hidden" id="ticketTId" name="ticketTId" value="0">
                    <button class="button button-brand confirmPayButtom" id="confirmPayButtom" disabled>Confirmar
                        compra</button>
                </form>
            </div>
            <!-- El contenido del menú se agregará aquí dinámicamente -->
        </div>
    </div>


    <script>
        const tickets = {!! json_encode($tickets) !!};
    </script>
@endsection
