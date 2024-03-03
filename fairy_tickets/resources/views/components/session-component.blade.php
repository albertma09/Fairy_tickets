@php
    use Carbon\Carbon;

    $fecha = Carbon::parse($session->date);

    // Configurar el idioma a español
    Carbon::setLocale('es');

    // Obtener el nombre completo del mes en español (ej. "enero")
    $nombreMes = $fecha->monthName;
@endphp
{{-- @dd($session); --}}
<div class="session-card">
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
            <a href="{{ route('generar.csv', ['session_id' => $session->id]) }}" class="button button-brand">generar
                csv</a>
        </div>
        <div>
            <button id="{{$session->id}}" class="button button-danger closeSale">Cierre de venta</button>
            <form id="close-sale-form-{{$session->id}}" action="{{ route('close.sale') }}" method="POST" style="display: none;">
                @csrf
                <input type="hidden" name="session_id" id="session_id" value="{{ $session->id }}">
            </form>
        </div>
        <div class="session-card-locPreu">
            <div class="session-card-data-loc">Total de ventas</div>
            <div class="session-card-data-price">{{ $session->count }}</div>
        </div>
    </div>
</div>

<x-close-sale-modal />
