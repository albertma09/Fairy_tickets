@extends('layouts.master')

@section('title', 'Nuevo Evento')

@section('content')

    <form class="default-form" action="{{ route('events.store') }}" method ="POST" enctype="multipart/form-data">
        @csrf
        <div class="container-half">
            <!-- Título del evento -->
            <div class="input-unit">
                <label for="title">Título del evento</label>
                <input type="text" id="title" name="title" required>
            </div>

            <!-- Categoría -->
            <div class="input-unit">
                <label for="category">Categoría</label>
                <select id="category" name="category" required>
                    <option value="cine">Cine</option>
                    <option value="conferencia">Conferencia</option>
                    <option value="danza">Danza</option>
                    <option value="musica">Musica</option>
                    <option value="teatro">Teatro</option>
                </select>
            </div>

            <!-- Imagen Principal -->
            <div class="input-unit">
                <label for="image">Imagen principal del evento</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <!-- Descripción -->
            <div class="input-unit">
                <label for="description">Descripción del evento</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>
            <!-- Cierre de la venta en línea -->
            <fieldset>
                <legend>Cierre de la venta online
                </legend>
                <div class="input-unit">
                    <div class="row-unit">
                        <input type="radio" id="withEvent" name="onlineSaleClosure" value="same"
                            class="onlinesale-closure-radio" checked>
                        <label for="withEvent">Hora de la celebración del evento</label>
                    </div>
                    <div class="row-unit">
                        <input type="radio" id="oneHBefore" name="onlineSaleClosure" value="onehour"
                            class="onlinesale-closure-radio">
                        <label for="oneHBefore">Una hora antes de la celebración del evento</label>
                    </div>
                    <div class="row-unit">
                        <input type="radio" id="twoHBefore" name="onlineSaleClosure" value="twohours"
                            class="onlinesale-closure-radio">
                        <label for="twoHBefore">Dos horas antes de la celebración del evento</label>
                    </div>
                    <div class="row-unit">
                        <input type="radio" id="customDatetime" name="onlineSaleClosure" value="cusrom"
                            class="onlinesale-closure-radio">
                        <label for="customDatetime">Personalizar fecha y hora de cierre de la venta online</label>
                    </div>
                </div>
                <div class="input-unit" id="customClosureDatetimeContainer">
                    <label for="onlineClosureDatetime">Indica la fecha y hora para establecer el momento del cierre de la
                        venta online</label>
                    <input type="datetime-local" id="onlineClosureDatetime" name="onlineClosureDatetime">
                </div>
            </fieldset>


            <!-- Evento oculto -->
            <div class="row-unit">
                <input type="checkbox" id="hidden_event" name="hidden_event">
                <label for="hidden_event">Ocultar evento</label>

            </div>

            <!-- Entradas nominales -->
            <div class="row-unit">
                <input type="checkbox" id="named_tickets" name="named_tickets">
                <label for="named_tickets">Entradas nominales</label>

            </div>

        </div>
        <div class="container-half">
            <!-- Dirección -->
            {{-- Select en el que si se elige nueva dirección, se abre un nuevo formulario para añadir una dirección nueva --}}
            <fieldset>
                <legend>Información de la ubicación del evento</legend>
                <div class="container-full">
                    <p>Primero elige si quieres seleccionar una de las ubicaciones usadas con anterioridad o añadir una
                        nueva:
                    </p>
                    <div class="row-unit">
                        <input type="radio" id="existingAddress" name="addressType" value="existing">
                        <label for="existingAddress">Ubicación ya existente</label>
                    </div>
                    <div class="row-unit">
                        <input type="radio" id="newAddress" name="addressType" value="new" checked>
                        <label for="newAddress">Nueva dirección</label>
                    </div>
                </div>
                <div id="existingAddressContainer" class=" input-unit container-full hidden">
                    <label for="addressId">Selecciona una dirección</label>
                    <select name="addressId" id="addressId">
                        <option value=""></option>
                    </select>
                </div>
                <div id="newAddressContainer" class="hidden container-full">
                    <div class="input-unit">
                        <label for="locationName">Nombre del recinto</label>
                        <input type="text" name="locationName" id="locationName">
                    </div>
                    <div class="input-unit">
                        <label for="locationCapacity">Aforo máximo</label>
                        <input type="text" name="locationCapacity" id="locationCapacity">
                    </div>
                    <div class="input-unit">
                        <label for="locationProvince">Provincia</label>
                        <input type="text" name="locationProvince" id="locationProvince">
                    </div>
                    <div class="input-unit">
                        <label for="locationProvince">Ciudad</label>
                        <input type="text" name="locationCity" id="locationCity">
                    </div>
                    <div class="input-unit">
                        <label for="locationProvince">Calle</label>
                        <input type="text" name="locationStreet" id="locationStreet">
                    </div>
                    <div class="input-unit">
                        <label for="locationProvince">Número</label>
                        <input type="text" name="locationNumber" id="locationNumber">
                    </div>
                    <div class="input-unit">
                        <label for="locationProvince">Código Postal</label>
                        <input type="text" name="locationCP" id="locationCP">
                    </div>
            </fieldset>
            <fieldset>
                <legend>Información de sesión</legend>

                <!-- Fecha y hora de la celebración del evento-->
                <div class="input-unit">
                    <label for="eventDatetime">Fecha y hora del evento</label>
                    <input type="datetime-local" id="eventDatetime" name="eventDatetime" required>
                </div>

                <!-- Nuevo formulario que relacionará fechas con aforos y entradas -->
                {{-- Nueva fecha -> formulario con aforo y entradas para esa fecha --}}
                <!-- Aforo máximo -->
                <div class="input-unit">
                    <label for="sessionMaxCapacity">Limitación de aforo (máx.)</label>
                    <input type="number" id="sessionMaxCapacity" name="sessionMaxCapacity" required>
                </div>

                <!-- Agregar elementos de formulario dinámicos para fechas/horas y capacidades adicionales -->

                <!-- Entradas -->
                <!-- Agregar elementos de formulario dinámicos para tipos de entrada -->

            </fieldset>
        </div>


        <button class="button button-brand" type="submit">Crear Evento</button>
    </form>
@endsection
