@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')
    @if ($opinion->isEmpty())
        
        <form action="{{ route('create-opinion') }}" class="default-form" method="post">
            @csrf

            <div class="grid-container">
                <input type="hidden" name="token" id="token" value="{{ $token }}">

                <div class="input-unit">
                    <input type="hidden" id="purchase_id" name="purchase_id" value="{{ $id['id'] }}">
                    <label for="nom">Nombre:</label>
                    <input type="text" id="name" name="name" required>

                </div>
                @error('nom')
                    <div class="msg-error">
                        El nombre introducido no es valido, por favor, introduzca un valor valido
                    </div>
                @enderror
                <div class="input-unit">
                    <label for="caretes">Que te ha parecido:</label>
                    <input type="hidden" id="face_rating" name="face_rating" required>
                    <div class="caret-container">
                        <img src="{{ asset('opinion/bad.png') }}" alt="Valoración 1" class="caret-icon" data-value="1" loading="lazy">
                        <img src="{{ asset('opinion/normal.png') }}" alt="Valoración 2" class="caret-icon" data-value="2" loading="lazy">
                        <img src="{{ asset('opinion/good.png') }}" alt="Valoración 3" class="caret-icon" data-value="3" loading="lazy">
                    </div>
                    @error('caretes')
                        <div class="msg-error">
                            Debes seleccionar una de los iconos para crear tu opinion
                        </div>
                    @enderror
                </div>
                <div>
                    <label for="puntuacio">Puntuación:</label>
                    <input type="hidden" id="star_rating" name="star_rating" required>
                    <div class="stars-container">
                        <span class="fa fa-star" data-value="5"></span>
                        <span class="fa fa-star" data-value="4"></span>
                        <span class="fa fa-star" data-value="3"></span>
                        <span class="fa fa-star" data-value="2"></span>
                        <span class="fa fa-star" data-value="1"></span>
                    </div>
                    @error('puntuacio')
                        <div class="msg-error">
                            Debes seleccionar un numero de estrellas para crear tu opinion
                        </div>
                    @enderror
                </div>
                <div class="input-unit">
                    <label for="titol">Titulo:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                @error('titol')
                    <div class="msg-error">
                        El titulo introducido no es valido, por favor, introduzca un valor valido
                    </div>
                @enderror
                <div class="input-unit">
                    <label for="comentari">Comentario:</label>
                    <textarea id="comment" name="comment" rows="4" required></textarea>
                </div>
                @error('comentari')
                    <div class="msg-error">
                        El comentario introducido no es valido, por favor, introduzca un valor valido
                    </div>
                @enderror
                <button type="submit" class="button button-brand">Enviar</button>
            </div>

        </form>

    @else
    
    <h2>¡Gracias por tu opinión!</h2>

    @endif

@endsection
