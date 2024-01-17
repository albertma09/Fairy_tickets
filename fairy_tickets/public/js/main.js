// Imports de módulos
import {
    setupAddressFormToggle,
    setupCustomiseOnlineClosureToggle,
} from "./modules/eventForm.js";
import {
    addMenuFunctionalities,
    delayedCloseMenuOnResize,
} from "./modules/navigation.js";

// Función que inicializa las funcionalidades para el eventform
const initializeEventForm = () => {
    // Getters de los botones y los containers
    const existingAddressRadio = document.getElementById("existingAddress");
    const newAddressRadio = document.getElementById("newAddress");
    const existingAddressContainer = document.getElementById(
        "existingAddressContainer"
    );
    const newAddressContainer = document.getElementById("newAddressContainer");
    const radioGroup = document.querySelectorAll(".onlineSaleClosure");
    const customDatetimeContainer = document.getElementById(
        "customClosureDatetimeContainer"
    );

    // Comprueba si existen (si estamos en la página adecuada)
    if (
        existingAddressRadio &&
        newAddressRadio &&
        existingAddressContainer &&
        newAddressContainer &&
        radioGroup &&
        customDatetimeContainer
    ) {
        // Llamada a la función que establece el toggle
        setupAddressFormToggle(
            existingAddressRadio,
            newAddressRadio,
            existingAddressContainer,
            newAddressContainer
        );
        setupCustomiseOnlineClosureToggle(radioGroup, customDatetimeContainer);
    }
};

// Llama a la función al cargar el documento
document.addEventListener("DOMContentLoaded", addMenuFunctionalities);

// Event listener del evento 'resize' de la ventana
window.addEventListener("resize", delayedCloseMenuOnResize);

document.addEventListener("DOMContentLoaded", function () {
    // Obtén el elemento del enlace de cierre de sesión por su ID
    const logoutLink = document.getElementById("logout-link");

    // Agrega un event listener para el clic en el enlace
    if (logoutLink) {
        logoutLink.addEventListener("click", function (event) {
            // Previene el comportamiento predeterminado del enlace
            event.preventDefault();

            // Muestra una alerta de confirmación y, si el usuario acepta, envía el formulario de cierre de sesión
            let isConfirmed = confirm("¿Estás seguro de cerrar sesión?");

            if (isConfirmed) {
                document.getElementById("logout-form").submit();
            }
        });
    }
    initializeEventForm();
});
