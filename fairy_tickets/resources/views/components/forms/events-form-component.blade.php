<form action="{{ route('location.store') }}" method="POST" class="dialog-form">
    @csrf
    <!--Formulario Nueva Dirección -->
    <h3>Añadir nueva ubicación de evento</h3>
    <div id="newAddressContainer" class="container-full">
        <div class="input-unit">
            <label for="locationName">Nombre del recinto</label>
            <input type="text" name="name" id="locationName" required />
        </div>
        <div class="input-unit">
            <label for="locationCapacity">Aforo máximo</label>
            <input type="text" name="capacity" id="locationCapacity" required />
        </div>
        <div class="input-unit">
            <label for="locationProvince">Provincia</label>
            <input type="text" name="province" id="locationProvince" required />
        </div>
        <div class="input-unit">
            <label for="locationCity">Ciudad</label>
            <input type="text" name="city" id="locationCity" required />
        </div>
        <div class="input-unit">
            <label for="locationStreet">Calle</label>
            <input type="text" name="street" id="locationStreet" required />
        </div>
        <div class="input-unit">
            <label for="locationNumber">Número</label>
            <input type="text" name="number" id="locationNumber" required />
        </div>
        <div class="input-unit">
            <label for="locationCP">Código Postal</label>
            <input type="text" name="cp" id="locationCP" required />
        </div>
        <button class="button button-brand" type=submit>Añadir Ubicación</button>
</form>
