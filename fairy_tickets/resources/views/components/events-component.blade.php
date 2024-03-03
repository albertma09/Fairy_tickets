@php
    use Carbon\Carbon;

    $fecha = Carbon::parse($event->date);

    // Configurar el idioma a español
    Carbon::setLocale('es');

    // Obtener el nombre completo del mes en español (ej. "enero")
    $nombreMes = $fecha->monthName;
@endphp
<div class="events-card">
    <div class="events-card-img img">
        <img srcset="{{ $event->mainSmImg }} 300w,
        {{ $event->mainMdImg }} 700w,
        {{ $event->mainBgImg }} 1600w"
            sizes="max-width: 767px 300px,
            (min-width: 768px) and (max-width: 1023px) 50vw,
            min-width: 1024px 33.3vw"
            src="large-image.jpg" alt="imagen principal del evento" loading="lazy">
    </div>
    <div class="events-card-data">
        <div class="events-card-data-name">{{ $event->name }}</div>
        <div class="events-card-data-content">
            <div class="events-card-data-content-date">
                <div class="events-card-data-content-date-day">{{ date('d', strtotime($event->date)) }}</div>
                <div class="events-card-data-content-date-monthYear">
                    <div class="events-card-data-content-date-monthYear-month">{{ ucfirst(substr($nombreMes, 0, 3)) }}
                    </div>
                    <div>{{ date('Y', strtotime($event->date)) }}</div>
                </div>
            </div>
            <div class="events-card-data-content-from">
                <div>Desde</div>
                <div class="events-card-data-content-from-price">{{ $event->price }} €</div>
            </div>
        </div>
        <div class="events-card-buy">
            <a href="{{ route('events.mostrar', ['id' => $event->id]) }}" class="button button-brand">Comprar</a>
        </div>
    </div>
</div>
