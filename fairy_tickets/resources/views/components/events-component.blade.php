@php
    use Carbon\Carbon;

    $fecha = Carbon::parse($event->date);

    // Configurar el idioma a español
    Carbon::setLocale('es');

    // Obtener el nombre completo del mes en español (ej. "enero")
    $nombreMes = $fecha->monthName;
@endphp
<div class="events-card">
    <div class="events-card-data-img">imagen</div>
    <div class="events-card-data-name">{{ $event->event }}</div>
    <div class="events-card-data">
        <div class="events-card-data-date">
            <div class="events-card-data-date-day">{{ date('d', strtotime($event->date)) }}</div>
            <div class="events-card-data-date-monthYear">
                <div class="events-card-data-date-monthYear-month">{{ ucfirst(substr($nombreMes, 0, 3)) }}</div>
                <div>{{ date('Y', strtotime($event->date)) }}</div>
            </div>
        </div>
        <div class="events-card-locPreu">
            <div class="events-card-data-loc">{{ $event->location }}</div>
            <div class="events-card-data-price">{{ $event->price }} €</div>
        </div>
    </div>
    <div class="events-card-buy">
        <a href="{{ route('events.mostrar', ['id' => $event->id]) }}" class="button button-brand">Comprar</a>
    </div>
</div>
