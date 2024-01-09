<div class="events-card">
    <div class="events-card-data">
        <div class="events-card-data-text">
            <div>{{ $event->name }}</div>
            <div>{{ $event->date }}</div>
            <div>{{ $event->location }}, {{ $event->city }}</div>
            <div>{{ $event->price }} €</div>
        </div>
        <div class="events-card-actions">
            <button class="icon-button-buy">Compra tus entradas</button>
            <button class="icon-button-details">+</button>
        </div>
    </div>
    <div class="events-card-data-img">imagen</div>
</div>
