/* Métodos y funcionalidades del formulario de eventos */

// Función que devuelve true si el radio con value custom está checkeado
const isCustomDateRadioChecked = (radioGroup) => {
    return Array.from(radioGroup).some(
        (radio) => radio.checked && radio.value === "custom"
    );
};

// Función que realiza el cambio de visibilidad del input personalizar datetime de cierre de venta online si se selecciona
export const setupCustomiseOnlineClosureToggle = (
    radioGroup,
    customContainer
) => {
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
};

// Función que realiza el cambio de visibilidad según el radio seleccionado
export const handleAddressTypeChange = (
    existingAddressRadio,
    newAddressRadio,
    existingAddressContainer,
    newAddressContainer
) => {
    // Comprueba qué botón radio está seleccionado y cambia la visibilidad con la clase hidden
    if (existingAddressRadio.checked) {
        existingAddressContainer.classList.remove("hidden");
        newAddressContainer.classList.add("hidden");
    } else if (newAddressRadio.checked) {
        existingAddressContainer.classList.add("hidden");
        newAddressContainer.classList.remove("hidden");
    }
};

// Función inicial que establece los listeners
export const setupAddressFormToggle = (
    existingAddressRadio,
    newAddressRadio,
    existingAddressContainer,
    newAddressContainer
) => {
    // Listeners para los radios
    if (existingAddressRadio && newAddressRadio) {
        existingAddressRadio.addEventListener("change", () => {
            handleAddressTypeChange(
                existingAddressRadio,
                newAddressRadio,
                existingAddressContainer,
                newAddressContainer
            );
        });
        newAddressRadio.addEventListener("change", () => {
            handleAddressTypeChange(
                existingAddressRadio,
                newAddressRadio,
                existingAddressContainer,
                newAddressContainer
            );
        });
    }

    // Lanza el estado inicial según se cargue la página
    handleAddressTypeChange(
        existingAddressRadio,
        newAddressRadio,
        existingAddressContainer,
        newAddressContainer
    );
};
