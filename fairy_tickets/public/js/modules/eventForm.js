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

// Función que pasa al controlador la id de una ubicación y espera sus datos
const fetchLocationData = async (locationId) => {
    try {
        const response = await fetch(`/Location/fetch?id=${locationId}`);
        const data = await response.json();

        return data;
    } catch (error) {
        console.error("Error fetching location data:", error);
    }
};

// Función que asigna un valor y un valor máximo al input de capacida de sesión
// según la capacidad que se le pasa
const changeSessionMaxCap = (capacity) => {
    const maxCapInput = document.getElementById("sessionMaxCapacity");
    maxCapInput.value = capacity;
    maxCapInput.max = capacity;
};

// Función que después de buscar los datos de la ubicación seleccionada
// llama la función que cambia la capacidad máx de sesión.
const assignMaxCapOnAddressChange = async (addressSelect) => {
    if (!isNaN(addressSelect.value)) {
    const locationData = await fetchLocationData(addressSelect.value);
    const capacity = locationData[0].capacity;
    changeSessionMaxCap(capacity);
    }
};

// Función que realiza el cambio de visibilidad según el radio seleccionado
const handleNewAddress = (newAddressDialog, addressSelect) => {
    // Comprueba qué botón radio está seleccionado y cambia la visibilidad con la clase hidden

    if (addressSelect.value === "new") {
        newAddressDialog.showModal();
    } else {
        newAddressDialog.close();
    }
};

// Función inicial que establece los listeners
export const setupAddressFormToggle = () => {
    // Getters del select y los containers
    const addressSelect = document.getElementById("addressId");
    const newAddressDialog = document.getElementById("newLocationDialog");

    // Si existen añade el listener las funcionalidades, por si no estamos en esa página
    if (addressSelect && newAddressDialog) {
        addressSelect.addEventListener("change", () => {
            handleNewAddress(newAddressDialog, addressSelect);
            assignMaxCapOnAddressChange(addressSelect);
        });
    }
};
