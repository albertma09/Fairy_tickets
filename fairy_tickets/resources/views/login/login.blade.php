<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="oscuro">

    <div class="container">
        <div class="center">
            <div class="login">
                <div class="title">
                    Iniciar Sesión
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="email" name="email" placeholder="Correo" required>
                    <br>
                    <input type="password" name="password" placeholder="Contraseña" required>
                    <br>
                    <button class="button-brand" type="submit">Iniciar Sesión</button>
                    @if (session('error'))
                        <p >{{ session('error') }}</p>
                    @endif
                </form>
                <div class="footer">
                    <a href="#">¿Olvidaste la contraseña?</a>
                </div>
            </div>
            <div class="inferior">
                <a href="{{ URL::previous() }}">Volver</a>
            </div>
        </div>
    </div>
</body>

</html>
