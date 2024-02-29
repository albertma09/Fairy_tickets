@extends('layouts.master')
@if (!isset($event))
@section('title', 'Nuevo Evento')
@else
@section('title', $event->name)
@endif

@section('content')

    {{-- @dd($event); --}}

    <form class="default-form" action="{{ isset($event) ? route('events.edit') : route('events.store') }}" method ="POST"
        enctype="multipart/form-data">
        @csrf
        <div class="separate-form">
            <div class="grid-container">
                <!-- Título del evento -->

                <div class="input-unit">
                    @if (isset($event))
                        <input type="hidden" id="event_id" name="event_id" value="{{ $event->id }}">
                    @endif
                    <label for="title">Título del evento</label>
                    <input type="text" id="title" name="name" value="{{ $event->name ?? old('name') }}"
                        maxlength="250" autofocus required />
                    @error('name')
                        <p class="msg-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Categoría -->
                <div class="input-unit">
                    <label for="category">Categoría</label>
                    <select id="category" name="category_id" required>
                        <option value="">Selecciona una opión</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $event->category_id ?? old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="msg-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div id="existingAddressContainer" class=" input-unit container-full">
                    <label for="addressId">Dirección del evento</label>
                    <select name="location_id" id="addressId">
                        <option value="">Selecciona una opción</option>
                        <option value="new">Añadir nueva dirección</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}"
                                {{ $event->location_id ?? old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}</option>
                        @endforeach
                        @if (session('newLocation') == !null)
                            <option value="{{ session('newLocation')['id'] }}" selected>
                                {{ session('newLocation')['name'] }}
                            </option>
                        @endif
                        @error('location_id')
                            <p class="msg-error">
                                {{ $message }}
                            </p>
                        @enderror
                    </select>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" />
                <!-- Imagen Principal -->
                @if (!isset($event))
                <div class="input-unit">
                    <label for="image">Imagen principal del evento</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    @if ($errors->has('image'))
                        <div class="msg-error">
                            {{ $errors->first('image') }}
                        </div>
                    @endif
                    @error('image')
                        <p class="msg-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                @endif
                <!-- Descripción -->
                <div class="input-unit">
                    <label for="description">Descripción del evento</label>
                    <textarea id="description" name="description" rows="5">{{ $event->description ?? old('description') }}</textarea>
                    @error('description')
                        <p class="msg-error">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                @if (!isset($event))
                    <h3 class="form-section-title">Información de la primera sesión</h3>
                    <!-- Fecha de la celebración del evento-->
                    <div class="input-unit">
                        <label for='date'>Fecha de la primera sesión del evento</label>
                        <input type="date" id="date" name="session_date" value="{{ old('session_date') }}"
                            required />
                        @error('session_date')
                            <p class="msg-error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                    <!-- Hora de la celebración del evento-->
                    <div class="input-unit">
                        <div>
                            <p>Hora de inicio de la primera sesión</p>
                            @php
                                $inputName = 'session';
                            @endphp
                            <x-forms.time-input-component :inputName="$inputName" />
                        </div>
                    </div>

                    <!-- Nuevo formulario que relacionará fechas con aforos y entradas -->
                    <!-- Aforo máximo -->
                    <div class="input-unit">
                        <label for="session_capacity">Limitación de aforo (por defecto, la capacidad máxima del recinto)
                        </label>
                        <input type="number" id="session_capacity" name="session_capacity"
                            value="{{ old('session_capacity') ?? (session('newLocation')['capacity'] ?? '') }}"
                            max="{{ session('newLocation') == !null ? session('newLocation')['capacity'] : '' }}">
                        @error('session_capacity')
                            <p class="msg-error">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Entradas nominales -->
                    <div class="row-unit">
                        <input type="checkbox" id="named_tickets" name="named_tickets"
                            {{ old('named_tickets') ? 'checked' : '' }} />
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
                                {{ old('online_sale_closure') == 'custom' ? 'checked' : '' }}>
                            <label for="customDatetime">Personalizar fecha y hora de cierre de la venta online</label>
                        </div>
                    </div>
                    <div id="custom_closure_datetime_container">
                        <div class="input-unit">
                            <label for="custom_closure_date">Indica la fecha para establecer el momento
                                del cierre de
                                la
                                venta online</label>
                            <input type="date" id="custom_closure_date" name="custom_closure_date"
                                value="{{ old('custom_closure_date') }}">
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
                            <x-forms.time-input-component :inputName="$inputName" />
                        </div>

                    </div>

                    <div>
                        <h3 class="form-section-title">Tipos de entrada</h3>
                        <p>En esta sección podrás definir cuántas clases de entrada tendrá la primera sesión de tu evento,
                            así
                            como
                            su nombre y precio.
                            También podrás poner una cantidad máxima de entradas a la venta.</p>
                        <p class="msg-info">Ten en cuenta que la suma total no
                            podrá ser mayor que la capacidad máxima de tu sesión.</p>
                    </div>
                    @php
                        $index = 1;
                    @endphp
                    <div class="form-ticket-container" id="formTicketContainer">
                        <x-forms.tickettype-input-group-component :index="$index" :ticketType="null" />
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

                    <!-- Evento oculto -->
                    <div class="row-unit">
                        <input type="checkbox" id="hidden_event" name="hidden" {{ old('hidden') ? 'checked' : '' }} />
                        <label for="hidden_event">Ocultar evento</label>

                    </div>

                    <div>
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
                @endif
                <button class="button button-brand"
                    type="submit">{{ isset($event) ? 'Editar Evento' : 'Crear Evento' }}</button>
            </div>

            @if (isset($event))
                <div class="secondary-img-container">
                    <input type="file" name="imagenes[]" multiple accept="image/*">

                    <div class="scroll-secondary-img">
                        <div class="secondary-img">
                            <img
                                src="https://images.unsplash.com/photo-1580501170961-bb0dbf63a6df?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2970&q=80" />
                                <i class="fas fa-trash-alt icono"></i>
                        </div>
                        <div class="secondary-img">
                            <img
                                src="https://images.unsplash.com/photo-1580501170961-bb0dbf63a6df?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2970&q=80" />
                                <i class="fas fa-trash-alt icono"></i>
                        </div>
                        <div class="secondary-img">
                            <img
                                src="https://images.unsplash.com/photo-1580501170961-bb0dbf63a6df?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2970&q=80" />
                                <i class="fas fa-trash-alt icono"></i>
                        </div>
                        <div class="secondary-img">
                            <img
                                src="https://images.unsplash.com/photo-1580501170961-bb0dbf63a6df?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2970&q=80" />
                                <i class="fas fa-trash-alt icono"></i>
                        </div>
                    </div>

                </div>
            @endif
        </div>
    </form>
    <dialog id="newLocationDialog">
        <button class="button button-danger close-dialog-button">X</button>
        <x-forms.events-form-component />
    </dialog>
@endsection
@section('scripts')
    <script src="{{ asset('js/forms.js') }}"></script>
@endsection
