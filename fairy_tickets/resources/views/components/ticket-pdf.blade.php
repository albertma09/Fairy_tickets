<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <script src="https://kit.fontawesome.com/918614923c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ public_path('css/style.css') }}">
    <link rel="icon" href="{{ 'storage/img/covers/favicon.png' }}">
    <link rel='stylesheet' href='https://unpkg.com/leaflet@1.8.0/dist/leaflet.css' crossorigin='' />
</head>

<body class="pdf">



    <table>
        <tr>
            <td>
                <table class="inner-table first-table">
                    <tr>
                        <td colspan="3">
                            <img src="{{ public_path('logo/logoFairyTickets_fondoOscuro.png') }}"
                                alt="Logo del sitio web" class="logo-img" />
                        </td>
                    </tr>

                    <tr>
                        <td colspan="3">
                            <h3>{{$ticket->event_name}}</h3>
                            <p>{{$ticket->location_name}}, {{$ticket->city}} {{$ticket->province}}</p>
                            <p>{{$ticket->hour}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p>{{$day}}</p>
                        </td>
                        <td>
                            <p>{{$month}}</p>
                        </td>
                        <td>
                            <p>{{$year}}</p>
                        </td>
                    </tr>
                </table>
            </td>
            <td>
                <table class="inner-table second-table">
                    <tr>
                        <td>
                            <div class="qr-code">
                                <img src="data:image/png;base64, {!! $codigoQR !!}" alt="Código QR">
                                <p>{{$ticket->id}}</p>
                            </div>
                        </td>
                    </tr>
                    @if($ticket->client_name !== null && $ticket->dni !== null)
                    <tr>
                        <td>
                            <p>{{$ticket->client_name}}</p>
                            <p>{{$ticket->dni}}</p>
                        </td>
                    </tr>
                        
                        @endif
                    <tr>
                        
                        <td>
                            
                            <p>{{$ticket->ticket_type_name}}</p>
                            <p>{{$ticket->price}}€</p>


                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <hr>

    <h2>Descripción</h2>
    <p>
        {{$ticket->event_description}}
    </p>


    <footer></footer>
    <script type="module" src="{{ asset('js/main.js') }}"></script>
</body>

</html>
