@extends('layouts.mostrar-evento')

@section('title', $evento[0]->name)

@section('content')

    <div class="slider-container">
        <button class="prev-btn">&#10094;</button>
        <img class="slider-item"
            src="https://images.unsplash.com/photo-1580501170961-bb0dbf63a6df?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2970&q=80" />
        <img class="slider-item"
            src="https://images.unsplash.com/photo-1580501170888-80668882ca0c?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2940&q=80" />
        <img class="slider-item"
            src="https://images.unsplash.com/photo-1572508589584-94d778209dd9?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2940&q=80" />
        <button class="next-btn">&#10095;</button>
    </div>
    <div class="container">
        <h1 class="titulo-brand">
            {{ $evento[0]->name }}
        </h1>

        <p>
            {{ $evento[0]->description }}
        </p>
    </div>

    <div class="container">
      <h1 class="titulo-brand">
            sesiones
        </h1>
    </div>

    <div class="container">
      <h1 class="titulo-brand">
            mapa
        </h1>
    </div>
    {{-- @dd($evento) --}}

@endsection
