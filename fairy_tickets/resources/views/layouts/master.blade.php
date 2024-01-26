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
</head>

<body>
    <x-navigation-component />
    <main>
        <h1>@yield('title')</h1>
        <div>
            @yield('content')
        </div>
    </main>
    <footer></footer>
    <script type="module" src="{{ asset('js/main.js') }}"></script>
</body>

</html>
