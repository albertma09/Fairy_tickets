@extends('layouts.master')

@section('title', 'Nuevo Evento')

@section('content')

    <form class="default-form" action="{{ route('events.store') }}" method ="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid-container">
            <!-- Título del evento -->

            <div class="input-unit">
                <label for="title">Título del evento</label>
                <input type="text" id="title" name="name" value="{{ old('name') }}" maxlength="250" autofocus
                    required />
                @error('name')
                    <div class="msg-error">
                        El título del evento que has introducido es erróneo, por favor, vuelve a introducir un título
                        válido.
                    </div>
                @enderror
            </div>

            <!-- Categoría -->
            <div class="input-unit">
                <label for="category">Categoría</label>
                <select id="category" name="category_id" required>
                    <option value="">Selecciona una opión</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="msg-error">
                        Por favor, selecciona una categoría.
                    </div>
                @enderror
            </div>

            <div id="existingAddressContainer" class=" input-unit container-full">
                <label for="addressId">Dirección del evento</label>
                <select name="location_id" id="addressId">
                    <option value="">Selecciona una opción</option>
                    <option value="new">Añadir nueva dirección</option>
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}</option>
                    @endforeach
                    @if (session('newLocation') == !null)
                        <option value="{{ session('newLocation')['id'] }}" selected>{{ session('newLocation')['name'] }}
                        </option>
                    @endif
                    @error('location_id')
                        <div class="msg-error">
                            Por favor, selecciona una opción válida para determinar la ubicación del evento.
                        </div>
                    @enderror
                </select>
            </div>
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" />
            <!-- Imagen Principal -->
            <div class="input-unit">
                <label for="image">Imagen principal del evento</label>
                <input type="file" id="image" name="image" accept="image/*">
                @if ($errors->has('image'))
                    <div class="msg-error">
                        {{ $errors->first('image') }}
                    </div>
                @endif
                @error('image')
                    <div class="msg-error">
                        Ha habido algún error al subir la imágen, por favor, vuélvalo a intentar.
                    </div>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="input-unit">
                <label for="description">Descripción del evento</label>
                <textarea id="description" name="description" rows="5">{{ old('description') }}</textarea>
                @error('description')
                    <div class="msg-error">
                        No ha escrito una descripción correcta, por favor, vuelva a escribir la descripción del evento.
                    </div>
                @enderror
            </div>

            <h3 class="form-section-title">Información de la primera sesión</h3>
            <!-- Fecha y hora de la celebración del evento-->
            <div class="input-unit">
                <label for='datetime'>Fecha y hora del evento</label>
                <input type="datetime-local" id="datetime" name="sessionDatetime" value="{{ old('sessionDatetime') }}"
                    required />
                @error('sessionDatetime')
                    <div class="msg-error">
                        Por favor, elija unas fecha y hora correctas.
                    </div>
                @enderror
            </div>

            <!-- Nuevo formulario que relacionará fechas con aforos y entradas -->
            {{-- Nueva fecha -> formulario con aforo y entradas para esa fecha --}}
            <!-- Aforo máximo -->
            <div class="input-unit">
                <label for="sessionMaxCapacity">Limitación de aforo (por defecto, la capacidad máxima del recinto)
                </label>
                <input type="number" id="sessionMaxCapacity" name="sessionMaxCapacity"
                    value="{{ $errors->has('sessionMaxCapacity') ? old('sessionMaxCapacity') : session('newLocation')['capacity'] ?? '' }}"
                    max="{{ session('newLocation') == !null ? session('newLocation')['capacity'] : '' }}">
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
                    {{ old('named_tickets') ? 'checked' : '' }} />
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
                        class="onlinesale-closure-radio" {{ old('onlineSaleClosure') == 'custom' ? 'checked' : '' }}>
                    <label for="customDatetime">Personalizar fecha y hora de cierre de la venta online</label>
                </div>
            </div>
            <div class="input-unit" id="customClosureDatetimeContainer">
                <label for="onlineClosureDatetime">Indica la fecha y hora para establecer el momento del cierre de
                    la
                    venta online</label>
                <input type="datetime-local" id="onlineClosureDatetime" name="customSaleClosure"
                    value="{{ old('customSaleClosure') }}">
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
            <button class="button button-brand" type="submit">Crear Evento</button>
    </form>
    <dialog id="newLocationDialog">
        <button class="button button-danger close-dialog-button">X</button>
        <x-forms.events-form-component />
    </dialog>
@endsection
