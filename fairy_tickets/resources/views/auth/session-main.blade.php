<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="oscuro">

    <div class="container">
        <div class="center">
            <div class="login">
                <div class="title">
                    @yield('title')
                </div>
                @yield('content')
            </div>
            <div class="inferior">
                <a href="{{ route('home.index') }}">Volver</a>
            </div>
        </div>
    </div>
    <script src="{{asset('js/main.js')}}"></script>
</body>

</html>
