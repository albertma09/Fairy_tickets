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
                <div class="input-unit">
                    <label for="caretes">Que te ha parecido:</label>
                    <input type="hidden" id="face_rating" name="face_rating" required>
                    <div class="caret-container">
                        <img src="{{ asset('opinion/bad.png') }}" alt="Valoración 1" class="caret-icon" data-value="1"
                            loading="lazy">
                        <img src="{{ asset('opinion/normal.png') }}" alt="Valoración 2" class="caret-icon" data-value="2"
                            loading="lazy">
                        <img src="{{ asset('opinion/good.png') }}" alt="Valoración 3" class="caret-icon" data-value="3"
                            loading="lazy">
                    </div>

                    @if ($errors->has('face_rating'))
                        <p class="msg-error">{{ $errors->first('face_rating') }}</p>
                    @endif
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
                </div>
                @if ($errors->has('star_rating'))
                    <p class="msg-error">{{ $errors->first('star_rating') }}</p>
                @endif
                <div class="input-unit">
                    <label for="titol">Titulo:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="input-unit">
                    <label for="comentari">Comentario:</label>
                    <textarea id="comment" name="comment" rows="4" required></textarea>
                </div>
                @if (Session::has('error'))
                    <p class="msg-error">{{ Session::get('error') }}</p>
                @endif
                <button type="submit" class="button button-brand">Enviar</button>
            </div>

        </form>
    @else
        <h2>¡Gracias por tu opinión!</h2>

    @endif

@endsection
