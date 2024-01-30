
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <script src="https://kit.fontawesome.com/918614923c.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" href="{{ asset('storage/img/covers/favicon.png') }}">
    <link rel='stylesheet' href='https://unpkg.com/leaflet@1.8.0/dist/leaflet.css' crossorigin='' />
</head>

<body class="ticket">

        <div class="ticket-content">
            <div class="img-ticket">
                <img class="slider-item" src="https://images.unsplash.com/photo-1580501170961-bb0dbf63a6df?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=2970&q=80" />
                
            </div>
            <div class="event-info">
                <div class="date">
                    <p class="day">dia</p>
                    <p class="month">mes</p>
                    <p class="year">año</p>
                </div>
                <div class="info">
                    <h2>Fairy tickets</h2>
                    <h3>Nombre evento</h3>
                    <p>hora inicio</p>
                </div>
                <div class="location">
                    <p>nombre local, ciudad, provincia</p>
                    
                </div>
            </div>
            <div class="ticket-type-info">
                contenido ticket
            </div>
        </div>

    <footer></footer>
    <script type="module" src="{{ asset('js/main.js') }}"></script>
</body>

</html>
