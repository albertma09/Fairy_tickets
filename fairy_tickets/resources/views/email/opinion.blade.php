<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Valoracion</title>
</head>
<body>
    <h2>{{$opinion->event_name}}</h2>
    <p>Hola {{$opinion->name}}, aqui tienes un enlace para dar tu opinion sobre el evento</p>
    <a href="{{route('user-opinion', ['token'=> $token])}}">Formulario opinion personal</a>
</body>
</html>