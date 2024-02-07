@extends('layouts.master')

@section('title', 'Nueva Sesión')

@section('content')
    <form class="default-form" action="{{ route('sessions.store') }}" method ="POST">
        @csrf
        <div class="grid-container">

            <!-- Fecha y hora de la celebración del evento-->
            <div class="input-unit">
                <label for='datetime'>Fecha y hora del evento</label>
                <input type="datetime-local" id="datetime" name="sessionDatetime"
                    value="{{ old('sessionDatetime') !== null ? old('sessionDatetime') : $sessionData['session']->date . 'T' . $sessionData['session']->hour }}"
                    required />
                @error('sessionDatetime')
                    <div class="msg-error">
                        Por favor, elija unas fecha y hora correctas.
                    </div>
                @enderror
            </div>

            <!-- Aforo máximo -->
            <div class="input-unit">
                <label for="sessionMaxCapacity">Limitación de aforo (recuerda: la capacidad máxima del recinto es
                    {{ $location->capacity }}.)
                </label>
                <input type="number" id="sessionMaxCapacity" name="sessionMaxCapacity"
                    value="{{ $errors->has('sessionMaxCapacity') ? old('sessionMaxCapacity') : $sessionData['session']->session_capacity ?? '' }}"
                    max="{{ session('newLocation') == !null ? $location->capacity : '' }}">
                @error('sessionMaxCapacity')
                    <div class="msg-error">
                        No ha introducido un dato correcto, recuerde que la capacidad de la sesión no puede superar el aforo del
                        recinto dónde se celebra el evento.
                    </div>
                @enderror
            </div>

            <!-- Entradas nominales -->
            <div class="row-unit">
                <input type="checkbox" id="named_tickets" name="named_tickets"
                    {{ old('named_tickets') || ($sessionData['session']->nominal_tickets ?? false) ? 'checked' : '' }} />
                <label for="named_tickets">Entradas nominales</label>
            </div>

            <!-- Cierre de la venta en línea -->
            <div class="input-unit">
                <p>Indica el momento del cierre de la venta de entradas en línea</p>
                <div class="row-unit">
                    <input type="radio" id="withEvent" name="onlineSaleClosure" value="0"
                        class="onlinesale-closure-radio"
                        {{ old('onlineSaleClosure') == 0 || old('onlineSaleClosure') === null ? 'checked' : '' }}>
                    <label for="withEvent">Hora de la celebración del evento</label>
                </div>
                <div class="row-unit">
                    <input type="radio" id="oneHBefore" name="onlineSaleClosure" value="1"
                        class="onlinesale-closure-radio" {{ old('onlineSaleClosure') == 1 ? 'checked' : '' }}>
                    <label for="oneHBefore">Una hora antes de la celebración del evento</label>
                </div>
                <div class="row-unit">
                    <input type="radio" id="twoHBefore" name="onlineSaleClosure" value="2"
                        class="onlinesale-closure-radio" {{ old('onlineSaleClosure') == 2 ? 'checked' : '' }}>
                    <label for="twoHBefore">Dos horas antes de la celebración del evento</label>
                </div>
                <div class="row-unit">
                    <input type="radio" id="customDatetime" name="onlineSaleClosure" value="custom"
                        class="onlinesale-closure-radio"
                        {{ old('onlineSaleClosure') == null || old('onlineSaleClosure') == 'custom' ? 'checked' : '' }}>
                    <label for="customDatetime">Personalizar fecha y hora de cierre de la venta online</label>
                </div>
            </div>
            <div class="input-unit" id="customClosureDatetimeContainer">
                <label for="onlineClosureDatetime">Indica la fecha y hora para establecer el momento del cierre de
                    la
                    venta online</label>
                <input type="datetime-local" id="onlineClosureDatetime" name="customSaleClosure"
                    value="{{ old('customSaleClosure') !== null ? old('customSaleClosure') : $sessionData['session']->online_sale_closure }}">
                @error('customSaleClosure')
                    <div class="msg-error">
                        Por favor, elija unas fecha y hora correctas.
                    </div>
                @enderror
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
