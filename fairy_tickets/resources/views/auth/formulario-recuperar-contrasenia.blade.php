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
                    Recuperar contraseña
                </div>
                @if (Session::has('message'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('message') }}
                    </div>
                @endif

                <form action="{{ route('enviar-recuperacion') }}" method="POST">
                    @csrf
                    <input type="text" id="email_address" class="form-control" name="email" placeholder="email" required>
                    @if ($errors->has('email'))
                        <p class="text-danger">{{ $errors->first('email') }}</p>
                    @endif
                    <button type="submit" class="button-brand">
                        Recuperar contraseña
                    </button>

                </form>

            </div>
            <div class="inferior">
                <a href="{{ URL::previous() }}">Volver</a>
            </div>
        </div>
    </div>
</body>

</html>
