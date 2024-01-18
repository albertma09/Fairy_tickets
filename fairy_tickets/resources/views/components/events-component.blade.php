<div class="events-card">
    <div class="events-card-data-img">imagen</div>
    <div class="events-card-data-name">{{$event->event}}</div>
    <div class="events-card-data">
        <div class="events-card-data-date">
            <div class="events-card-data-date-day">dia</div>
            <div class="events-card-data-date-monthYear">
                <div>mes</div>
                <div>año</div>
            </div>
        </div>
        <div class="events-card-locPreu">
            <div class="events-card-data-loc">{{$event->location}}</div>
            <div class="events-card-data-price">{{$event->price}}</div>
        </div>
    </div>
    <a class="button button-brand">Comprar</a>
</div>