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
                <div class="navigation-map">
                    <h3>Navigation Map</h3>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Legal notices</a></li>
                    </ul>
                </div>
                <div class="promoter-link">
                    <h3>Promoter Link</h3>
                    <a href=" {{ auth()->check() ? route('promotor', ['userId' => auth()->user()->id]) : route('login') }}">Promoter's Page</a>
                   
                </div>
            </footer>
        </div>
        
    </div>
    
    <script type="module" src="{{ asset('js/main.js') }}"></script>
</body>

</html>
