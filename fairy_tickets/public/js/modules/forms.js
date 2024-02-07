/* Métodos y funcionalidades del formulario de eventos */

// Función que devuelve true si el radio con value custom está checkeado
const isCustomDateRadioChecked = (radioGroup) => {
    return Array.from(radioGroup).some(
        (radio) => radio.checked && radio.value === "custom"
    );
};

// Función que recibe por parámetros un elemento y una condición.
// Oculta el elemento si la condición se cumple y viceversa.
const hideElement = (element, condition) => {
    element.classList.toggle("hidden", !condition);
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
        hideElement(customContainer, isCustomDateRadioChecked(radioGroup));

        // Listeners para cambiar el estado cuando haya un cambio
        radioGroup.forEach((radio) => {
            radio.addEventListener("change", () => {
                // Used the hideElement function
                hideElement(
                    customContainer,
                    isCustomDateRadioChecked(radioGroup)
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

// Función que asigna un valor y un valor máximo al input de capacidad de sesión
// según la capacidad que se le pasa, también se lo asigna a la creación del ticket inicial
const changeSessionMaxCap = (capacity, maxCapInput, ticketQtyInput) => {
    maxCapInput.value = capacity;
    maxCapInput.max = capacity;
    ticketQtyInput.max = capacity;
};

// Función que después de buscar los datos de la ubicación seleccionada
// llama las funciones que cambian la capacidad máx de sesión a todos los elementos del formulario
// que lo necesitan.
const assignMaxCapToForm = async (
    addressSelect,
    maxCapInput,
    ticketQtyInput
) => {
    if (!isNaN(addressSelect.value)) {
        const locationData = await fetchLocationData(addressSelect.value);
        const capacity = locationData[0].capacity;
        changeSessionMaxCap(capacity, maxCapInput, ticketQtyInput);
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

// Función inicial que establece las funcionalidades del formulario de dirección y lo relacionado con la capacidad máxima
export const setupAddressFormToggle = () => {
    // Getters del select y los containers
    const addressSelect = document.getElementById("addressId");
    const newAddressDialog = document.getElementById("newLocationDialog");
    const maxCapInput = document.getElementById("sessionMaxCapacity");
    const ticketQtyInput = document.getElementById("ticketQuantity");

    // Si existen añade el listener las funcionalidades, por si no estamos en esa página
    if (addressSelect && newAddressDialog && maxCapInput) {
        addressSelect.addEventListener("change", () => {
            handleNewAddress(newAddressDialog, addressSelect);
            assignMaxCapToForm(addressSelect, maxCapInput, ticketQtyInput);
        });
        maxCapInput.addEventListener("change", () => {
            ticketQtyInput.max = maxCapInput.value;
        });
    }
};

// Función que devuelve el número más alto entre los grupos de input de ticket
const findTicketWithHighestNumber = () => {
    let highestNumber = 0;

    // Itera todos los elementos cuya id empiece por: 'formTicketUnit'
    document.querySelectorAll('[id^="formTicketUnit"]').forEach((element) => {
        // Extraemos el número del id
        const idNumber = parseInt(element.id.replace("formTicketUnit", ""), 10);

        // Mira si el número es más alto que el actual más alto
        if (!isNaN(idNumber) && idNumber > highestNumber) {
            highestNumber = idNumber;
        }
    });

    return highestNumber;
};

// Función que se encarga de añadir un nuevo grupo de inputs para un nuevo tipo de ticket
const addNewTicketInputGroup = (ticketContainer, firstTicket) => {
    const newTicketType = firstTicket.cloneNode(true);
    let ticketIdCounter = findTicketWithHighestNumber()+1;
    console.log(ticketIdCounter);
    newTicketType.id = `formTicketUnit${ticketIdCounter}`;
    newTicketType.querySelector(
        "h4"
    ).textContent = `Tipo de entrada ${ticketIdCounter}`;

    // Cambiamos los ids para que no se repitan
    const ticketTypeName = newTicketType.querySelector("#ticketDescription1");
    ticketTypeName.id = `ticketDescription${ticketIdCounter}`;
    ticketTypeName.previousElementSibling.setAttribute(
        "for",
        `ticketDescription${ticketIdCounter}`
    );

    const price = newTicketType.querySelector("#price1");
    price.id = `price${ticketIdCounter}`;
    price.previousElementSibling.setAttribute("for", `price${ticketIdCounter}`);

    const ticketQuantity = newTicketType.querySelector("#ticketQuantity1");
    ticketQuantity.id = `ticketQuantity${ticketIdCounter}`;
    ticketQuantity.previousElementSibling.setAttribute(
        "for",
        `ticketQuantity${ticketIdCounter}`
    );

    // Añadimos el contenedor al padre
    ticketContainer.appendChild(newTicketType);
};

// Función que se encarga de eliminar el último grupo de inputs para un tipo de ticket
const removeLastTicketInputGroup = (ticketContainer) => {
    const ticketGroups = ticketContainer.children;

    // Nos aseguramos que hay más de un contenedor antes de eliminarlo
    if (ticketGroups.length > 1) {
        ticketGroups[ticketGroups.length - 1].remove();
    }
};

// Función que establece las funcionalidades para los tipos de tickets: añadir nuevos tipos y eliminarlos
export const setupAddRemoveTicketTypes = () => {
    // Recogemos los botones, el container y el div a clonar
    const addTicketTypeBtn = document.querySelector("#addTicketType");
    const removeTicketTypeBtn = document.querySelector("#removeTicketType");
    const ticketContainer = document.querySelector("#formTicketContainer");
    const firstTicket = document.querySelector("#formTicketUnit1");
    if (
        addTicketTypeBtn &&
        removeTicketTypeBtn &&
        ticketContainer &&
        firstTicket
    ) {
        addTicketTypeBtn.addEventListener("click", (event) => {
            event.preventDefault();
            addNewTicketInputGroup(ticketContainer, firstTicket);
        });
        removeTicketTypeBtn.addEventListener("click", (event) => {
            event.preventDefault();
            removeLastTicketInputGroup(ticketContainer);
        });
    }
};
