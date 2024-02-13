<div>
    <div class="time-wrapper">
        <div class="time-input">
            <label for="{{ $inputName }}Hours">
                <input type="number" id="{{ $inputName }}Hours" value="{{ old($inputName . '_hours') ?? '00' }}"
                    name="{{ $inputName }}_hours" max="23" min="0" maxlength="2">
                <span class="label lbl-hrs">horas</span>
            </label>
            <span>:</span>
            <label for="{{ $inputName }}Minutes">
                <input type="number" id="{{ $inputName }}Minutes" value="{{ old($inputName . '_minutes') ?? '00' }}"
                    name="{{ $inputName }}_minutes" max="59" min="0" maxlength="2">
                <span class="label lbl-min">minutos</span>
            </label>
        </div>
    </div>
    @error('session_time')
        <div class="msg-error">
            Por favor, introduzca una hora correcta.
        </div>
    @enderror
</div>
