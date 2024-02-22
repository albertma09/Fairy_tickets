<form action="{{ route('location.store') }}" method="POST" class="dialog-form">
    @csrf
    <!--Formulario Nueva Dirección -->
    <h3>Añadir nueva ubicación de evento</h3>
    <div id="newAddressContainer" class="container-full">
        <div class="input-unit">
            <label for="locationName">Nombre del recinto</label>
            <input type="text" name="name" id="locationName" maxlength="150" required />
            @error('name')
                <p class="msg-error">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="input-unit">
            <label for="locationCapacity">Aforo máximo</label>
            <input type="number" name="capacity" id="locationCapacity" max="999999" min="0" required
                title="Sólo puede introducir valores numéricos de máximo 6 dígitos." />
            @error('capacity')
                <p class="msg-error">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="input-unit">
            <label for="locationProvince">Provincia</label>
            <input type="text" name="province" id="locationProvince" maxlength="100" required />
            @error('province')
                <p class="msg-error">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="input-unit">
            <label for="locationCity">Ciudad</label>
            <input type="text" name="city" id="locationCity" maxlength="100" required />
            @error('city')
                <p class="msg-error">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="input-unit">
            <label for="locationStreet">Calle</label>
            <input type="text" name="street" id="locationStreet" maxlength="150" required />
            @error('street')
                <p class="msg-error">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="input-unit">
            <label for="locationNumber">Número</label>
            <input type="number" name="number" id="locationNumber" max="999" min="0" required />
            @error('number')
                <p class="msg-error">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <div class="input-unit">
            <label for="locationCP">Código Postal</label>
            <input type="text" name="cp" id="locationCP" minlength="5" maxlength="6" pattern="[0-9]+"
                title="Sólo puede introducir valores numéricos de máximo 6 dígitos" required />
            @error('cp')
                <p class="msg-error">
                    {{ $message }}
                </p>
            @enderror
        </div>
        <button class="button button-brand" type=submit>Añadir Ubicación</button>
</form>
