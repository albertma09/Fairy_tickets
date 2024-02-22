<div>
    <div class="time-wrapper">
        <div class="time-input">
            <label for="{{ $inputName }}Hours">
                <input type="number" id="{{ $inputName }}Hours" value="{{ old($inputName . '_hours') ?? $hours ?? '00' }}"
                    name="{{ $inputName }}_hours" max="23" min="0" maxlength="2">
                <span class="label lbl-hrs">horas</span>
            </label>
            <span>:</span>
            <label for="{{ $inputName }}Minutes">
                <input type="number" id="{{ $inputName }}Minutes" value="{{ old($inputName . '_minutes') ?? $minutes ?? '00' }}"
                    name="{{ $inputName }}_minutes" max="59" min="0" maxlength="2">
                <span class="label lbl-min">minutos</span>
            </label>
        </div>
    </div>
    @if ($errors->has('{{ $inputName }}_hours'))
    <div class="msg-error">
        Por favor, introduzca una valor numérico correcto para las horas.
    </div>
    @endif
    @if ($errors->has('{{ $inputName }}_minutes'))
    <div class="msg-error">
        Por favor, introduzca una valor numérico correcto para los minutos.
    </div>
    @endif
</div>
