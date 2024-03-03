<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description"
        content="Fairy Tickets, vive tus eventos favoritos como si estuvieras en un cuento de hadas. 
        Encuentra los mejores eventos a los mejores precios que se realizarán en tu ciudad, no te quedes
        sin tu entrada." />
    <meta property="og:description"
        content="Fairy Tickets, vive tus eventos favoritos como si estuvieras en un cuento de hadas. 
        Encuentra los mejores eventos a los mejores precios que se realizarán en tu ciudad, no te quedes
        sin tu entrada." />
    <title>@yield('title')</title>
    <script src="https://kit.fontawesome.com/918614923c.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/logo/favicon.png') }}">
    <link rel="shortcut icon" sizes="192x192" href="{{ asset('/logo/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="grid-container">
        <x-navigation-component />
        <div class="body-container">
            <main>
                <h1 class="titulo titulo-cabecera">@yield('title')</h1>
                <div class="main-content">
                    @yield('content')
                </div>
            </main>
            <footer class="footer">
                <x-footer-component />
            </footer>
        </div>

    </div>

    <script type="module" src="{{ asset('js/main.js') }}"></script>
    @yield('scripts')
</body>

</html>
