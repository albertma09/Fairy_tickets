@extends('layouts.master')

@section('title', 'Nuevo Evento')

@section('content')

    <form class="default-form" action="{{ route('events.store') }}" method ="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-half">
            <!-- Título del evento -->
            <div class="input-unit">
                <label for="title">Título del evento</label>
                <input type="text" id="title" name="name" required>
            </div>

            <!-- Categoría -->
            <div class="input-unit">
                <label for="category">Categoría</label>
                <select id="category" name="category_id" required>
                    <option value="8">Cine</option>
                    <option value="3">Conferencia</option>
                    <option value="5">Danza</option>
                    <option value="7">Musica</option>
                    <option value="1">Teatro</option>
                </select>
            </div>

            <div id="existingAddressContainer" class=" input-unit container-full">
                <label for="addressId">Dirección del evento</label>
                <select name="location_id" id="addressId">
                    <option value="">Selecciona una opción</option>
                    <option value="new">Añadir nueva dirección</option>
                    @foreach ($locations as $location)
                        <option value="{{$location->id}}">{{ $location->name }}</option>
                    @endforeach
                </select>
                <div id="addressShowCase">

                </div>
            </div>
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <!-- Imagen Principal -->
            {{-- <div class="input-unit">
                <label for="image">Imagen principal del evento</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div> --}}

            <!-- Descripción -->
            <div class="input-unit">
                <label for="description">Descripción del evento</label>
                <textarea id="description" name="description" rows="4"></textarea>
            </div>

            <!-- Fecha y hora de la celebración del evento-->
            <div class="input-unit">
                <label for='datetime'>Fecha y hora del evento</label>
                <input type="datetime-local" id="datetime" name="sessionDatetime">
            </div>

            <!-- Nuevo formulario que relacionará fechas con aforos y entradas -->
            {{-- Nueva fecha -> formulario con aforo y entradas para esa fecha --}}
            <!-- Aforo máximo -->
            <div class="input-unit">
                <label for="sessionMaxCapacity">Limitación de aforo (sesión) </label>
                <input type="number" id="sessionMaxCapacity" name="sessionMaxCapacity">
            </div>

            <!-- Agregar elementos de formulario dinámicos para fechas/horas y capacidades adicionales -->

            <!-- Entradas -->
            <!-- Agregar elementos de formulario dinámicos para tipos de entrada -->

            <!-- Cierre de la venta en línea -->
            <fieldset>
                <legend>Cierre de la venta online
                </legend>
                <div class="input-unit">
                    <div class="row-unit">
                        <input type="radio" id="withEvent" name="onlineSaleClosure" value="0"
                            class="onlinesale-closure-radio" checked>
                        <label for="withEvent">Hora de la celebración del evento</label>
                    </div>
                    <div class="row-unit">
                        <input type="radio" id="oneHBefore" name="onlineSaleClosure" value="1"
                            class="onlinesale-closure-radio">
                        <label for="oneHBefore">Una hora antes de la celebración del evento</label>
                    </div>
                    <div class="row-unit">
                        <input type="radio" id="twoHBefore" name="onlineSaleClosure" value="2"
                            class="onlinesale-closure-radio">
                        <label for="twoHBefore">Dos horas antes de la celebración del evento</label>
                    </div>
                    <div class="row-unit">
                        <input type="radio" id="customDatetime" name="onlineSaleClosure" value="custom"
                            class="onlinesale-closure-radio">
                        <label for="customDatetime">Personalizar fecha y hora de cierre de la venta online</label>
                    </div>
                </div>
                <div class="input-unit" id="customClosureDatetimeContainer">
                    <label for="onlineClosureDatetime">Indica la fecha y hora para establecer el momento del cierre de la
                        venta online</label>
                    <input type="datetime-local" id="onlineClosureDatetime" name="customSaleClosure" value="">
                </div>
            </fieldset>


            <!-- Evento oculto -->
            <div class="row-unit">
                <input type="checkbox" id="hidden_event" name="hidden">
                <label for="hidden_event">Ocultar evento</label>

            </div>

            <!-- Entradas nominales -->
            <div class="row-unit">
                <input type="checkbox" id="named_tickets" name="named_tickets">
                <label for="named_tickets">Entradas nominales</label>

            </div>
            <button class="button button-brand" type="submit">Crear Evento</button>
    </form>
    @if (session('success'))
        <dialog id="successDialog">
            <h3>¡Operación realizada con éxito!</h3>
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            <button class="button button-brand close-dialog-button">Ok</button>
        </dialog>
    @endif

    @if (session('error'))
        <dialog id="errorDialog">
            <div class="alert alert-danger">
                <h3>¡Atención!</h3>
                {{ session('error') }}
            </div>
            <button class="button button-brand close-dialog-button">Ok</button>

        </dialog>
    @endif
    <dialog id="newLocationDialog">
        <button class="button button-danger close-dialog-button">X</button>
        <x-forms.events-form-component />
    </dialog>
@endsection
