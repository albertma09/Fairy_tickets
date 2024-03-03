<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <script src="https://kit.fontawesome.com/918614923c.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="image/png" href="{{ asset('/logo/favicon.png') }}">
    <link rel="shortcut icon" sizes="192x192" href="{{ asset('/logo/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <x-navigation-component />
    <main class="centrado">
        
            @yield('content')
       
    </main>
    <footer class="footer">
        <x-footer-component />
    </footer>
    <script type="module" src="{{ asset('js/main.js') }}"></script>
</body>

</html>
