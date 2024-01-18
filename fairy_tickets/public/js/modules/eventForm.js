/* Métodos y funcionalidades del formulario de eventos */

// Función que devuelve true si el radio con value custom está checkeado
const isCustomDateRadioChecked = (radioGroup) => {
    return Array.from(radioGroup).some(
        (radio) => radio.checked && radio.value === "custom"
    );
};

// Función que realiza el cambio de visibilidad del input personalizar datetime de cierre de venta online si se selecciona
export const setupCustomiseOnlineClosureToggle = () => {
    const radioGroup = document.querySelectorAll(".onlinesale-closure-radio");
    const customContainer = document.getElementById(
        "customClosureDatetimeContainer"
    );
    // Si existen, lanza las funcionalidades
    if (radioGroup && customContainer) {
        // Estado inicial
        customContainer.classList.toggle(
            "hidden",
            !isCustomDateRadioChecked(radioGroup)
        );
    
        // Listeners para cambiar el estado cuando haya un cambio
        radioGroup.forEach((radio) => {
            radio.addEventListener("change", () => {
                customContainer.classList.toggle(
                    "hidden",
                    !isCustomDateRadioChecked(radioGroup)
                );
            });
        });
        
    }
};

// Función que realiza el cambio de visibilidad según el radio seleccionado
export const handleAddressTypeChange = (
    existingAddressRadio,
    newAddressRadio,
    newAddressDialog
) => {
    // Comprueba qué botón radio está seleccionado y cambia la visibilidad con la clase hidden
    if (existingAddressRadio.checked) {
        newAddressDialog.close();
    } else if (newAddressRadio.checked) {
        newAddressDialog.showModal();
    }
};

// Función inicial que establece los listeners
export const setupAddressFormToggle = () => {
    // Getters de los botones y los containers
    const existingAddressRadio = document.getElementById("existingAddress");
    const newAddressRadio = document.getElementById("newAddress");
    const newAddressDialog = document.getElementById("newLocationDialog");

    // Si existen lanza las funcionalidades, por si no estamos en esa página
    if (existingAddressRadio && newAddressRadio && newAddressDialog) {
        // Listeners para los radios
        if (existingAddressRadio && newAddressRadio) {
            existingAddressRadio.addEventListener("change", () => {
                handleAddressTypeChange(
                    existingAddressRadio,
                    newAddressRadio,
                    newAddressDialog
                );
            });
            newAddressRadio.addEventListener("change", () => {
                handleAddressTypeChange(
                    existingAddressRadio,
                    newAddressRadio,
                    newAddressDialog
                );
            });
        }

        // Lanza el estado inicial según se cargue la página
        handleAddressTypeChange(
            existingAddressRadio,
            newAddressRadio,
            newAddressDialog
        );
    }
};
