@extends('layouts.master')

@section('title', 'Nuevo Evento')

@section('content')

    <form class="default-form" action="{{ route('events.store') }}" method ="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid-container">
            <!-- Título del evento -->

            <div class="input-unit">
                <label for="title">Título del evento</label>
                <input type="text" id="title" name="name" value="{{ old('name') }}" autofocus required />
                @error('name')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Categoría -->
            <div class="input-unit">
                <label for="category">Categoría</label>
                <select id="category" name="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
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
                        <div class="alert alert-danger">
                            {{ $message }}
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
                    <div class="alert alert-danger">
                        {{ $errors->first('image') }}
                    </div>
                @endif
                @error('image')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="input-unit">
                <label for="description">Descripción del evento</label>
                <textarea id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @if ($errors->has('description'))
                    <div class="alert alert-danger">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                @error('description')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Fecha y hora de la celebración del evento-->
            <div class="input-unit">
                <label for='datetime'>Fecha y hora del evento</label>
                <input type="datetime-local" id="datetime" name="sessionDatetime" value="{{ old('sessionDatetime') }}"
                    required />
                @error('sessionDatetime')
                    <div class="alert alert-danger">
                        {{ $message }}
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
                    <div class="alert alert-danger">
                        {{ $message }}
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
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <p>En esta sección podrás definir cuántas clases de entrada tendrá la primera sesión de tu evento, así
                    como
                    su nombre y precio.
                    También podrás poner una cantidad máxima de entradas a la venta.</p>
                <small>Ten en cuenta que la suma total no
                    podrá ser mayor que la capacidad máxima de tu sesión.</small>
            </div>
            <div>
                <p>Añade un nuevo tipo de entrada</p>
                <div class="dual-button-container">
                    <button class="button button-brand" id="addTicketType"><i class="fas fa-plus"></i>
                    </button>
                    <button class="button button-danger" id="removeTicketType"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="form-ticket-container" id="formTicketContainer">
                <div class="form-ticket-unit" id="formTicketUnit">
                    <h4>Tipo de entrada 1</h4>
                    <div class="input-unit">
                        <label for="ticketDescription1">Nombre del tipo de entrada</label>
                        <input type="text" name="ticketDescription[]" id="ticketDescription1" />
                    </div>
                    <div class="input-unit">
                        <label for="price1">Precio</label>
                        <input type="text" name="price[]" pattern="\d{1,4}(?:\,\d{2})?"
                            title="Sólo puedes usar números, y máximo 4 numeros enteros." value="0" id="price1"
                            placeholder="0000,00" />
                    </div>
                    <div class="input-unit">
                        <label for="ticketQuantity1">Cantidad de entradas a la venta (opcional)</label>
                        <input type="number" min="0"
                            max="{{ session('newLocation') == !null ? session('newLocation')['capacity'] : '' }}"
                            name="ticketQuantity[]" id="ticketQuantity1"
                            title="Sólo puedes usar números y no no puede ser mayor a la capacidad máxima indicada">
                    </div>
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
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        <h3>¡Atención!</h3>
                        {{ session('error') }}
                        @if ($errors->any())
                            <div class="alert alert-danger">
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
