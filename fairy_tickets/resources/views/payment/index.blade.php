@extends('layouts.master')

@section('title', 'Resumen de la compra')

@section('content')

    <div class="summary-content">
        <div class="summary-content-event">
            <div class="titulo-seccion">Información del evento</div>
            <div class="summary-content-purchase">Evento:
                <div>{{ $sessionData[0]->name }}</div>
            </div>
            <div class="summary-content-purchase">Fecha:
                <div>{{ $sessionData[0]->date }}</div>
            </div>
            <div class="summary-content-purchase">Hora:
                <div>{{ $sessionData[0]->hour }}</div>
            </div>
        </div>
        <div class="summary-content-event">
            <div class="titulo-seccion">Datos de la compra</div>
            <div id="summaryPurchase"></div>
        </div>
    </div>
    <div class="additionalInformation">
        <form class="default-form" action="{{ route('payment.payToRedsys') }}" method ="POST">
            @csrf
            @if ($sessionData[0]->nominal_tickets === true)
                <div class="titulo-seccion">Datos de los asistentes</div>
                <div class="contentFields">
                    <template id="templateForm">
                        <div id="form-ticket-unit" class="form-ticket-unit">
                            <div class="titulo-seccion-asistente form-ticket-title">Asistente # 1</div>
                            <!-- Nombre del asistente -->

                            <div class="input-unit">
                                <label for="nameAssistant">Nombre del asistente</label>
                                <input type="text" id="nameAssistant" name="name[]" value="{{ old('name') }}"
                                    maxlength="250" autofocus required />
                            </div>

                            <!-- DNI -->
                            <div class="input-unit">
                                <label for="dniAssistant">DNI del asistente</label>
                                <input type="text" id="dniAssistant" name="dni[]" value="{{ old('dni') }}"
                                    maxlength="9" autofocus required />
                            </div>

                            <!-- movil -->
                            <div class="input-unit">
                                <label for="mobileAssistant">Movil del asistente</label>
                                <input type="number" id="mobileAssistant" name="mobile[]" value="{{ old('movil') }}"
                                    maxlength="9" autofocus required />
                            </div>
                            <div>
                                <input type="hidden" id="ticket" name="ticket_id[]">
                            </div>
                        </div>
                    </template>
                </div>
            @endif
            <div class="titulo-seccion">Datos del comprador</div>
            <div class="contentFields">
                <div class="form-ticket-unit">
                    <!-- Nombre del comprador -->

                    <div class="input-unit">
                        <label for="nameOwner">Nombre</label>
                        <input type="text" id="nameOwner" name="owner" maxlength="250" autofocus required />
                        <div name="error" id="error_nameOwner"></div>
                    </div>

                    <!-- DNI -->
                    <div class="input-unit">
                        <label for="dniOwner">DNI</label>
                        <input type="text" id="dniOwner" name="dniOwner" maxlength="9" autofocus required />
                        <div name="error" id="error_dniOwner"></div>
                    </div>

                    <!-- Email -->
                    <div class="input-unit">
                        <label for="emailOwner">Email</label>
                        <input type="email" id="emailOwner" name="emailOwner" maxlength="250" autofocus required />
                        <div name="error" id="error_emailOwner"></div>
                    </div>

                    <!-- Movil -->
                    <div class="input-unit">
                        <label for="mobileOwner">Movil</label>
                        <input type="number" id="mobileOwner" name="mobileOwner" max="999999999" autofocus required />
                        <div name="error" id="error_mobileOwner"></div>
                    </div>
                    <div id="inputs-hidden">
                        <input type="hidden" id="ticketsIdOwner" name="ticketsIdOwner">
                        <input type="hidden" id="ticketsQuantityOwner" name="ticketsQuantityOwner">
                        <input type="hidden" name="session_id" value="{{ $sessionData[0]->id }}">
                        <input type="hidden" name="priceToRedsys" id="priceToRedsys">
                    </div>
                </div>
            </div>
            <button id="pay" class="button button-brand confirmPayButton" type="submit" disabled>Pagar</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/modules/payment.js') }}"></script>
@endsection
