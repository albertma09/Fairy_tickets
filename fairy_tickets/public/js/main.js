// Imports de módulos


import {
    setupAddressFormToggle,
    setupCustomiseOnlineClosureToggle,
} from "./modules/eventForm.js";
import {
    addMenuFunctionalities,
    delayedCloseMenuOnResize,
} from "./modules/navigation.js";
import { commonButtonsSetup } from "./modules/buttons.js";

import { openModal } from './modules/popup.js';

// Función que inicializa las funcionalidades para el eventform
const initializeEventForm = () => {
    setupAddressFormToggle();
    setupCustomiseOnlineClosureToggle();
};


// Event listener del evento 'resize' de la ventana
window.addEventListener("resize", delayedCloseMenuOnResize);

// Llama a la función al cargar el documento
document.addEventListener("DOMContentLoaded", function () {
    // Obtén el elemento del enlace de cierre de sesión por su ID
    const logoutLink = document.getElementById("logout-link");
    const logoutDialog = document.getElementById('logoutDialog');
    const cancelButton = document.getElementById('cancelButton');
    const confirmButton = document.getElementById('confirmButton');
    // Agrega un event listener para el clic en el enlace
    if (logoutLink) {
        logoutLink.addEventListener("click", function (event) {
            logoutDialog.showModal();


        });
    }

    cancelButton.addEventListener('click', () => {
        logoutDialog.close();
    });

    confirmButton.addEventListener('click', () => {
        document.getElementById("logout-form").submit();
        logoutDialog.close();
    });

    addMenuFunctionalities();
    initializeEventForm();
    commonButtonsSetup();
    openModal();
});
