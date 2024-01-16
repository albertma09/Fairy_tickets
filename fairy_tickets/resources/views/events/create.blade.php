@extends('layouts.master')

@section('title', 'Nuevo Evento')

@section('content')

<form class="default-form" action="">
    @csrf
      <!-- Título del evento -->
      <div>
        <label for="title">Título del evento:</label>
        <input type="text" id="title" name="title" required>
    </div>

        <!-- Categoría -->
        <div>
        <label for="category">Categoría:</label>
        <select id="category" name="category" required>
            <option value="cine">Cine</option>
            <option value="conferencia">Conferencia</option>
            <option value="danza">Danza</option>
            <option value="musica">Musica</option>
            <option value="teatro">Teatro</option>
        </select>
</div>
        <!-- Dirección -->
        {{-- Select en el que si se elige nueva dirección, se abre un nuevo formulario para añadir una dirección nueva --}}
        <div>
        <label for="address">Dirección:</label>
        <select id="category" name="category" required>
            <option value="new">Nueva dirección</option>
        </select>

        </div>

        <!-- Imagen Principal -->
        <div>
        <label for="image">Imagen principal del evento:</label>
        <input type="file" id="image" name="image" accept="image/*" required>
    </div>

        <!-- Descripción -->
        <div>
        <label for="description">Descripción del evento:</label>
        <textarea id="description" name="description" rows="4" required></textarea>
        </div>

        <!-- Fecha y hora de la celebración del evento-->
        <div>
        <label for="datetime">Fecha y hora del evento:</label>
        <input type="datetime-local" id="datetime" name="datetime" required>
        </div>

        <!-- Nuevo formulario que relacionará fechas con aforos y entradas -->
        {{-- Nueva fecha -> formulario con aforo y entradas para esa fecha --}}
        <!-- Aforo máximo -->
        <div>
        <label for="max_capacity">Aforo máximo:</label>
        <input type="number" id="max_capacity" name="max_capacity" required>
        </div>

        <!-- Agregar elementos de formulario dinámicos para fechas/horas y capacidades adicionales -->

        <!-- Entradas -->
        <!-- Agregar elementos de formulario dinámicos para tipos de entrada -->

        <!-- Cierre de la venta en línea -->
        <label for="online_sale_closure">Cierre de la venta en línea:</label>
        {{-- Quizás un select¿? --}}
        <!-- Agregar opciones para la hora de cierre -->

        <!-- Evento oculto -->
        <div>
        <label for="hidden_event">Ocultar evento:</label>
        <input type="checkbox" id="hidden_event" name="hidden_event">

        </div>

        <!-- Entradas nominales -->
        <div>
        <label for="named_tickets">Entradas nominales:</label>
        <input type="checkbox" id="named_tickets" name="named_tickets">

        </div>

        <button class="button-brand" type="submit">Crear Evento</button>
</form>
@endsection
