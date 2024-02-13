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
    const numberOfTickets = document.createElement("input");
    numberOfTickets.size = 1;
    const addQuantityText = document.createElement("p");
    addQuantityText.textContent = 'Ingresa la cantidad';
    
    // plus.classList.add("fas", "fa-plus");
    // minus.classList.add("fas", "fa-minus");
    // plusButton.appendChild(plus);
    // minusButton.appendChild(minus);
    // plusButton.classList.add("button", "button-brand");
    // minusButton.classList.add("button", "button-danger");
    
    numberOfTickets.value = "0";
    let selectedQuantity = 0;
    // plusButton.addEventListener("click", function () {
    //     // Incrementar el valor solo si no excede un límite (puedes ajustar este límite)
    //     if (parseInt(numberOfTickets.value) < ticket.ticket_amount) {
    //         numberOfTickets.value = String(
    //             parseInt(numberOfTickets.value) + 1
    //         );
    //         const ticketPrice = parseFloat(ticket.price);
    //         updateTotal(ticketPrice, finalPrice);
    //     }
    // });

    // minusButton.addEventListener("click", function () {
    //     // Decrementar el valor solo si no es menor que cero
    //     if (parseInt(numberOfTickets.value) > 0) {
    //         numberOfTickets.value = String(
    //             parseInt(numberOfTickets.value) - 1
    //         );
    //         const ticketPrice = parseFloat(ticket.price);
    //         updateTotal((-ticketPrice), finalPrice);
    //     }
    // });

    const calculateTotal = (quantity) => {
        return function () {
            let newValue = parseInt(numberOfTickets.value);
            if (isNaN(newValue) || newValue < 0) {
                numberOfTickets.value = 0;
                newValue = numberOfTickets.value;
            }

            if (numberOfTickets.value > ticket.ticket_amount) {
                numberOfTickets.value = ticket.ticket_amount;
            }

            if (quantity < numberOfTickets.value) {
                const ticketPrice =
                    (numberOfTickets.value - quantity) * ticket.price;
                updateTotal(ticketPrice, finalPrice);
            } else if (quantity > numberOfTickets.value) {
                const ticketPrice =
                    (quantity - numberOfTickets.value) * ticket.price;
                updateTotal(-ticketPrice, finalPrice);
            }

            quantity = parseInt(numberOfTickets.value); 

           
        
        };
    };

    numberOfTickets.addEventListener("blur", calculateTotal(selectedQuantity));
    
    
    buyContainer.appendChild(numberOfTickets);
    buyContainer.appendChild(addQuantityText);
    // buyContainer.appendChild(minusButton);
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
        
        document
            .querySelectorAll(".sesion-card .button-brand")
            .forEach((button) => {
                button.addEventListener("click", function () {
                   
                    popupContainer.style.display = "flex";
                    Object.entries(tickets).forEach(([ticketId, ticket]) => {
                        if (ticket.session_id == button.id) {
                            
                            buildTicketContainer(ticket, ticketTypesContainer, finalPrice);
                            
                        }
                    });
                });
            });

        
        closePopupButton.addEventListener("click", function () {
            resetContainer(ticketTypesContainer, finalPrice, popupContainer);
        });

        
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

// Snippet que carga los modales de feedback en todas las páginas
const feedbackDialog = document.querySelector('dialog.fb-dialog');
if(feedbackDialog){
    feedbackDialog.showModal()
}