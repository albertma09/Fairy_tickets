<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tickets PDF</title>
</head>
<body>
    <h2>{{$event->name}}</h2>
    <p>Descripción: {{$event->description}}</p>
    <p>Fecha: {{$event->date}}</p>
    <p>Hora: {{$event->hour}}</p>
    <a href="{{route('events.mostrar', ['id'=> $session_id ])}}">Enlace al evento</a>
    <a href="{{ route('buy-ticket', ['session_id' => 234, 'email' => 'avis.beahan@example.org']) }}">Descargar entradas</a>
</body>
</html>