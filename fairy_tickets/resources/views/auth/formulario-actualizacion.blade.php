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
                    Actualizar contraseña
                </div>
                <form action="{{ route('actualizar-contrasenia') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    
                        <label for="email_address">Email</label>
                        
                            <input type="text" id="email_address" class="form-control" name="email" required>
                            
                        

                    
                        <label for="password" >Contraseña</label>
                        
                            <input type="password" id="password" class="form-control" name="password" required autofocus>
                            
                        

                    
                        <label for="password-confirm" >Confirmar Contraseña</label>
                        
                            <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus>

                            @if ($errors->has('email'))
                                <p>{{ $errors->first('email') }}</p>
                            @endif
                            @if ($errors->has('password'))
                                <p>{{ $errors->first('password') }}</p>
                            @endif
                            @if ($errors->has('password_confirmation'))
                                <p>{{ $errors->first('password_confirmation') }}</p>
                            @endif
                        

                    
                        <button type="submit" class="button-brand">
                            Cambiar Contraseña
                        </button>
                    
                </form>
            </div>
        </div>
    </div>
</body>

</html>
