@extends('layouts.master')

@section('title', 'Nueva Sesión')

@section('content')
    <form class="default-form" action="{{ route('sessions.store') }}" method ="POST">
        @csrf
        <input type="hidden" name="event_id"
            value="{{ old('event_id') ? old('event_id') : $sessionData['session']['event_id'] }}" />
        <div class="grid-container">

            <!-- Fecha de la celebración de la sesion-->
            <div class="input-unit">
                <label for="date">Fecha de la sesión</label>
                <input type="date" id="date" name="session_date"
                    value="{{ old('session_date') ?? $sessionData['session']['date'] }}" required autofocus />
                @error('session_date')
                    <p class="msg-error">
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <!-- Hora de la celebración del evento-->
            <div class="input-unit">
                <div>
                    <p>Hora de inicio de la sesión</p>
                    @php
                        $inputName = 'session';
                    @endphp
                    <x-forms.time-input-component :inputName="$inputName" :hours="$sessionData['session']['hours'] ?? 0" :minutes="$sessionData['session']['minutes'] ?? 0" />
                </div>
            </div>

            <!-- Aforo máximo -->
            <div class="input-unit">
                <label for="session_capacity">Limitación de aforo (recuerda: la capacidad máxima del recinto es
                    {{ $location->capacity }}.)
                </label>
                <input type="number" id="session_capacity" name="session_capacity"
                    value="{{ $errors->has('session_capacity') ? old('session_capacity') : $sessionData['session']['session_capacity'] ?? '' }}"
                    max="{{ session('newLocation') == !null ? $location->capacity : '' }}">
                @error('session_capacity')
                    <p class="msg-error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Entradas nominales -->
            <div class="row-unit">
                <input type="checkbox" id="named_tickets" name="named_tickets"
                    {{ old('named_tickets') || ($sessionData['session']['nominal_tickets'] ?? false) ? 'checked' : '' }} />
                <label for="named_tickets">Entradas nominales</label>
            </div>

            <!-- Cierre de la venta en línea -->
            <div class="input-unit">
                <p>Indica el momento del cierre de la venta de entradas en línea</p>
                <div class="row-unit">
                    <input type="radio" id="withEvent" name="online_sale_closure" value="0"
                        class="onlinesale-closure-radio"
                        {{ old('online_sale_closure') == 0 || old('online_sale_closure') === null ? 'checked' : '' }}>
                    <label for="withEvent">Hora de la celebración del evento</label>
                </div>
                <div class="row-unit">
                    <input type="radio" id="oneHBefore" name="online_sale_closure" value="1"
                        class="onlinesale-closure-radio" {{ old('online_sale_closure') == 1 ? 'checked' : '' }}>
                    <label for="oneHBefore">Una hora antes de la celebración del evento</label>
                </div>
                <div class="row-unit">
                    <input type="radio" id="twoHBefore" name="online_sale_closure" value="2"
                        class="onlinesale-closure-radio" {{ old('online_sale_closure') == 2 ? 'checked' : '' }}>
                    <label for="twoHBefore">Dos horas antes de la celebración del evento</label>
                </div>
                <div class="row-unit">
                    <input type="radio" id="customDatetime" name="online_sale_closure" value="custom"
                        class="onlinesale-closure-radio"
                        {{ old('online_sale_closure') == null || old('online_sale_closure') == 'custom' ? 'checked' : '' }}>
                    <label for="customDatetime">Personalizar fecha y hora de cierre de la venta online</label>
                </div>
            </div>
            <div id="custom_closure_datetime_container">
                <div class="input-unit">
                    <label for="custom_closure_date">Indica la fecha para establecer el momento del cierre de la venta
                        online</label>
                    <input type="date" id="custom_closure_date" name="custom_closure_date"
                        value="{{ old('custom_closure_date') ?? $sessionData['session']['custom_closure_date'] }}">
                    @error('custom_closure_date')
                        <p class="msg-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="input-unit">
                    <p>Indica la hora del momento del cierre de la venta online</p>
                    @php
                        $inputName = 'custom_closure';
                    @endphp
                    <x-forms.time-input-component :inputName="$inputName" :hours="$sessionData['session']['custom_closure_hours'] ?? 0" :minutes="$sessionData['session']['custom_closure_minutes'] ?? 0" />
                </div>
            </div>
            <div>
                <h3 class="form-section-title">Tipos de entrada</h3>
                <p>En esta sección podrás definir cuántas clases de entrada tendrá la primera sesión de tu evento, así
                    como
                    su nombre y precio.
                    También podrás poner una cantidad máxima de entradas a la venta.</p>
                <p class="msg-info">Ten en cuenta que la suma total no
                    podrá ser mayor que la capacidad máxima de tu sesión.</p>
            </div>
            <div class="form-ticket-container" id="formTicketContainer">
                @php
                    $index = 1;

                @endphp
                @foreach ($sessionData['tickettypes'] as $ticketType)
                    <x-forms.tickettype-input-group-component :index="$index" :ticketType="$ticketType" />
                    @php
                        $index++;

                    @endphp
                @endforeach

            </div>
            <div class="add-remove-ticket">

                <p>Añade un nuevo tipo de entrada o elimina el último</p>
                <div class="dual-button-container">
                    <button class="button button-brand" id="addTicketType"><i class="fas fa-plus"></i>
                    </button>
                    <button class="button button-danger" id="removeTicketType"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            </fieldset>

            <div>
                @if (session('success'))
                    <h3>¡Operación realizada con éxito!</h3>
                    <div class="msg-correct">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="msg-error">
                        <h3>¡Atención!</h3>
                        {{ session('error') }}
                        @if ($errors->any())
                            <div>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
            <button class="button button-brand" type="submit">Añadir Sesión</button>
        </div>
    </form>
@endsection
@section('scripts')
    <script src="{{ asset('js/forms.js') }}"></script>
@endsection
