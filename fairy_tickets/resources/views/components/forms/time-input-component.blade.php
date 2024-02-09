
<div id="time_wrapper">
    <div id="time_input">
      <label for="hours">
        <input type="number" id="{{ $inputName }}Hours" value="00" name="{{ $inputName }}_hours">
        <span class="label lbl-hrs">horas</span>
      </label>
      <span>:</span>
      <label for="minutes">
        <input type="number" id="{{ $inputName }}Minutes" value="00" name="{{ $inputName }}_minutes">
        <span class="label lbl-min">minutos</span>
      </label>
      {{-- <span>:</span>
      <label for="seconds">
        <input type="number" id="seconds" value="00">
        <span class="label lbl-sec">seconds</span>
      </label> --}}
    </div>
  </div>
  <div id="error" class="msg-error">This is an error</div>