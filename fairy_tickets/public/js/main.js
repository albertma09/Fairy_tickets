// Imports de módulos
import {
    addMenuFunctionalities,
    delayedCloseMenuOnResize,
} from "./modules/navigation.js";
import { commonButtonsSetup } from "./modules/buttons.js";

import { ticketSalesModalSetup, logoutModal, closeSaleModal, activateButtonConfirm } from './modules/modals.js';

import { addValues } from './modules/opinionForm.js';

// Función que inicializa las funcionalidades para el eventform
const initializeEventForm = () => {
    setupAddressFormToggle();
    setupCustomiseOnlineClosureToggle();
    setupAddRemoveTicketTypes();
};

// Event listener del evento 'resize' de la ventana
window.addEventListener("resize", delayedCloseMenuOnResize);

// Llama a la función al cargar el documento
document.addEventListener("DOMContentLoaded", function () {
    addMenuFunctionalities();
    commonButtonsSetup();
    ticketSalesModalSetup();
    activateButtonConfirm();
    addValues();
    logoutModal();
    closeSaleModal();
});


// summaryPurchaseInfo();