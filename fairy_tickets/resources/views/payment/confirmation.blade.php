@extends('layouts.master')

@section('title', 'Fairy Tickets')

@section('content')

    @if ($response['txnSuccess'] === true)
        <div class="success-payment">
            <div class="success-payment-title">
                <div class="titulo-seccion">{{ $response['title'] }}</div>
                <div class="titulo-subtitulo">{{ $response['message'] }}</div>
            </div>
            <div class="success-payment-info">
                <div>Fecha:</div>
                <div>{{ $response['date'] }}</div>
                <div>Hora:</div>
                <div>{{ $response['hour'] }}</div>
                <div>Nro. de orden:</div>
                <div>{{ $response['orderNumber'] }}</div>
                <div>Nro. de autorización:</div>
                <div>{{ $response['authCode'] }}</div>
            </div>
        </div>
        <div class="return-home"><a class="button button-brand" href="{{route('home.index')}}"> Volver al inicio</a></div>
    @else
        <div class="error-payment">
            <div class="error-payment-title">
                <div class="titulo-seccion">{{ $response['title'] }}</div>
                <div class="titulo-subtitulo">{{ $response['message'] }}</div>
            </div>
            <div class="error-payment-info">
                <div>Fecha:</div>
                <div>{{ $response['date'] }}</div>
                <div>Hora:</div>
                <div>{{ $response['hour'] }}</div>
                <div>Nro. de orden:</div>
                <div>{{ $response['orderNumber'] }}</div>
                <div>Error:</div>
                <div>{{ $response['responseCode'] }}</div>
            </div>
            <div class="error-payment-title">
                <div class="titulo-subtitulo">Para mas información, consulta el código de error {{$response['responseCode']}}</div>
                <div class="error-payment-url"><a class="error-payment-url" href="{{$response['url_description']}}">{{$response['url_description']}}</a></div>
            </div>
        </div>
        <div class="return-home"><a class="button button-brand" href="{{route('home.index')}}">Volver al inicio</a></div>
    @endif

@endsection
