<div class="form-ticket-unit" id="formTicketUnit{{ $index }}">
    <h4 class="form-ticket-title">Tipo de entrada {{ $index }}</h4>
    <div class="input-unit">
        <label for="ticketDescription{{ $index }}">Nombre del tipo de entrada</label>
        <input type="text" name="ticketDescription[]" id="ticketDescription{{ $index }}"
            value="{{ old('ticketDescription.' . ($index - 1)) ?: ($ticketType ? $ticketType->description : '') }}" />
    </div>
    <div class="input-unit">
        <label for="price{{ $index }}">Precio</label>
        <input type="text" name="price[]" pattern="\d{1,4}([,.]\d{1,2})?"
            title="Sólo puedes usar números, y máximo 4 numeros enteros"
            value="{{old('price.' . ($index - 1)) !== null ? old('price.' . ($index - 1)) : ($ticketType ? $ticketType->price : '0000,00') }}"
            id="price{{ $index }}" placeholder="0000,00" />
    </div>
    <div class="input-unit">
        <label for="ticketQuantity{{ $index }}">Cantidad de entradas a la venta (opcional)</label>
        <input type="number" min="0"
            max="{{ session('newLocation') !== null ? session('newLocation')['capacity'] : '' }}"
            name="ticketQuantity[]" id="ticketQuantity{{ $index }}"
            value ="{{ old('ticketQuantity.' . ($index - 1)) !== null ? old('ticketQuantity.' . ($index - 1)) : ($ticketType ? $ticketType->ticket_amount : '') }}"
            title="Sólo puedes usar números y no no puede ser mayor a la capacidad máxima indicada">
    </div>
</div>