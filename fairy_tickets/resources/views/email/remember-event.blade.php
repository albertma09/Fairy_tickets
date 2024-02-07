<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tickets PDF</title>
</head>
<body>

    {{-- @dd($event) --}}
    <h2>{{$event->event_name}}</h2>
    <p>Queda 1 dia para el inicio del evento!</p>
    <a href="{{route('events.mostrar', ['id'=> $event->id ])}}">Enlace al evento</a>
    <a href="{{ route('buy-ticket', ['session_id' => $event->session_id, 'email' => $event->email]) }}">Descargar entradas</a>
</body>
</html>