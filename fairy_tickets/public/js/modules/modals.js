let totalTickets = 0;

const updateTotal = (priceChange, finalPrice) => {
    // Actualizar el total sumando o restando el cambio de precio
    totalTickets += priceChange;

    // Mostrar el total en el elemento con id 'final-price'
    finalPrice.textContent = `Total: ${totalTickets.toFixed(2)}€`;
};

const buildBuyContainer = (ticket, buyContainer, finalPrice) => {
    const plusButton = document.createElement("button");
    const plus = document.createElement("i");
    const minusButton = document.createElement("button");
    const minus = document.createElement("i");
    const numberOfTickets = document.createElement("p");

    plus.classList.add("fas", "fa-plus");
    minus.classList.add("fas", "fa-minus");
    plusButton.appendChild(plus);
    minusButton.appendChild(minus);
    plusButton.classList.add("button", "button-brand");
    minusButton.classList.add("button", "button-danger");

    numberOfTickets.textContent = "0";

    plusButton.addEventListener("click", function () {
        // Incrementar el valor solo si no excede un límite (puedes ajustar este límite)
        if (parseInt(numberOfTickets.textContent) < ticket.ticket_amount) {
            numberOfTickets.textContent = String(
                parseInt(numberOfTickets.textContent) + 1
            );
            const ticketPrice = parseFloat(ticket.price);
            updateTotal(ticketPrice, finalPrice);
        }
    });

    minusButton.addEventListener("click", function () {
        // Decrementar el valor solo si no es menor que cero
        if (parseInt(numberOfTickets.textContent) > 0) {
            numberOfTickets.textContent = String(
                parseInt(numberOfTickets.textContent) - 1
            );
            const ticketPrice = parseFloat(ticket.price);
            updateTotal((-ticketPrice), finalPrice);
        }
    });

    buyContainer.appendChild(numberOfTickets);
    buyContainer.appendChild(plusButton);
    buyContainer.appendChild(minusButton);
};

const buildInfoContainer = (ticket, informationContainer) => {
    const ticketPrice = document.createElement("p");
    const ticketDescription = document.createElement("p");

    ticketPrice.textContent = `${ticket.price}€`;
    ticketDescription.textContent = `${ticket.ticket_types_description}`;

    informationContainer.appendChild(ticketPrice);
    informationContainer.appendChild(ticketDescription);
};

const buildTicketContainer = (ticket, ticketTypesContainer, finalPrice) => {
    const ticketContainer = document.createElement("div");
    const informationContainer = document.createElement("div");
    const buyContainer = document.createElement("div");

    ticketContainer.classList.add("ticket-container");
    informationContainer.classList.add("information-container");
    buyContainer.classList.add("buy-container");

    buildInfoContainer(ticket, informationContainer);
    buildBuyContainer(ticket, buyContainer, finalPrice);

    ticketContainer.appendChild(informationContainer);
    ticketContainer.appendChild(buyContainer);

    ticketTypesContainer.appendChild(ticketContainer);
};

const resetContainer = (ticketTypesContainer, finalPrice, popupContainer) => {
    ticketTypesContainer.innerHTML = "";
    finalPrice.innerHTML = "Total: 0.00€";
    totalTickets = 0;
    popupContainer.style.display = "none";
};

export const ticketSalesModalSetup = () => {
    const popupContainer = document.querySelector(".popup-container");
    const closePopupButton = document.querySelector(".close-popup");
    const ticketTypesContainer = document.getElementById(
        "ticket-types-container"
    );
    const finalPrice = document.getElementById("final-price");

    if (
        popupContainer &&
        closePopupButton &&
        ticketTypesContainer &&
        finalPrice
    ) {
        // Agregar evento de clic al botón "Comprar" para mostrar el menú emergente
        document
            .querySelectorAll(".sesion-card .button-brand")
            .forEach((button) => {
                button.addEventListener("click", function () {
                    // Mostrar el menú emergente
                    popupContainer.style.display = "flex";
                    Object.entries(tickets).forEach(([ticketId, ticket]) => {
                        if (ticket.session_id == button.id) {
                            // crear divs para almacenar la información
                            buildTicketContainer(ticket, ticketTypesContainer, finalPrice);
                        }
                    });
                });
            });

        // Agregar evento de clic al botón de cierre para ocultar el menú emergente
        closePopupButton.addEventListener("click", function () {
            resetContainer(ticketTypesContainer, finalPrice, popupContainer);
        });

        // Cerrar el menú emergente al hacer clic en el fondo oscuro
        popupContainer.addEventListener("click", function (event) {
            if (event.target === popupContainer) {
                resetContainer(
                    ticketTypesContainer,
                    finalPrice,
                    popupContainer
                );
            }
        });
    }
};
