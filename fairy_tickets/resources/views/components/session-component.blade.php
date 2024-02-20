@php
    use Carbon\Carbon;

    $fecha = Carbon::parse($session->date);

    // Configurar el idioma a español
    Carbon::setLocale('es');

    // Obtener el nombre completo del mes en español (ej. "enero")
    $nombreMes = $fecha->monthName;
@endphp
<div class="session-card">
    <div class="session-card-data-img img"><img
        src="https://images.unsplash.com/photo-1580501170961-bb0dbf63a6df?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2970&q=80" /></div>
    <div class="session-card-data-name">{{ $session->name }}</div>
    <div class="session-card-data">
        <div class="session-card-data-date">
            <div class="session-card-data-date-day">{{ date('d', strtotime($session->date)) }}</div>
            <div class="session-card-data-date-monthYear">
                <div class="session-card-data-date-monthYear-month">{{ ucfirst(substr($nombreMes, 0, 3)) }}</div>
                <div>{{ date('Y', strtotime($session->date)) }}</div>
            </div>
        </div>
        <div>
            <a href="{{route('generar.csv', ['session_id' => $session->id])}}" class="button button-brand">generar csv</a>
        </div>
        <div class="session-card-locPreu">
            <div class="session-card-data-loc">Total de ventas</div>
            <div class="session-card-data-price">100</div>
        </div>
    </div>
</div>
