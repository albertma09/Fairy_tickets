const popupContainer = document.querySelector('.popup-container');
const closePopupButton = document.querySelector('.close-popup');
const ticketTypesContainer = document.getElementById('ticket-types-container');
const finalPrice = document.getElementById('final-price');
let totalTickets = 0;
export const openModal = () => {


    // Agregar evento de clic al botón "Comprar" para mostrar el menú emergente
    document.querySelectorAll('.sesion-card .button-brand').forEach(function (button) {
        button.addEventListener('click', function () {
            // Mostrar el menú emergente
            popupContainer.style.display = 'flex';

            console.log(tickets);

            Object.entries(tickets).forEach(([ticketId, ticket]) => {

                if (ticket.session_id == button.id) {
                    // crear divs para almacenar la información
                    const ticketContainer = document.createElement('div');
                    const informationContainer = document.createElement('div');
                    const buyContainer = document.createElement('div');

                    ticketContainer.classList.add('ticket-container');
                    informationContainer.classList.add('information-container');
                    buyContainer.classList.add('buy-container');

                    ticketContainer.appendChild(informationContainer);
                    ticketContainer.appendChild(buyContainer);

                    const ticketPrice = document.createElement('p');
                    const ticketDescription = document.createElement('p');

                    ticketPrice.textContent = `${ticket.price}€`;
                    ticketDescription.textContent = `${ticket.ticket_types_description}`;

                    informationContainer.appendChild(ticketPrice);
                    informationContainer.appendChild(ticketDescription);

                    const plusButton = document.createElement('button');
                    const plus = document.createElement('i');
                    plus.classList.add('fas', 'fa-plus');
                    plusButton.appendChild(plus);
                    const minusButton = document.createElement('button');
                    const minus = document.createElement('i');
                    minus.classList.add('fas', 'fa-minus');
                    minusButton.appendChild(minus);
                    const numberOfTickets = document.createElement('p');
                    numberOfTickets.textContent = '0';

                    plusButton.classList.add('button', 'button-brand');
                    minusButton.classList.add('button', 'button-danger');

                    plusButton.addEventListener('click', function () {
                        // Incrementar el valor solo si no excede un límite (puedes ajustar este límite)
                        if (parseInt(numberOfTickets.textContent) < ticket.ticket_amount) {
                            numberOfTickets.textContent = String(parseInt(numberOfTickets.textContent) + 1);
                            const ticketPrice = parseFloat(ticket.price);
                            updateTotal(ticketPrice);
                        }
                    });

                    minusButton.addEventListener('click', function () {
                        // Decrementar el valor solo si no es menor que cero
                        if (parseInt(numberOfTickets.textContent) > 0) {
                            numberOfTickets.textContent = String(parseInt(numberOfTickets.textContent) - 1);
                            const ticketPrice = parseFloat(ticket.price);
                            updateTotal(-ticketPrice);
                        }
                    });


                    buyContainer.appendChild(numberOfTickets);
                    buyContainer.appendChild(plusButton);
                    buyContainer.appendChild(minusButton);

                    ticketTypesContainer.appendChild(ticketContainer);
                }
            });


            // Filtrar los tickets por la sesión actual
            // const sessionTickets = tickets.filter(ticket => ticket.session_id == sessionId);

            // Mostrar información de tickets en el contenedor
            // sessionTickets.forEach(function (ticket) {
            //     const ticketElement = document.createElement('p');
            //     ticketElement.textContent = `${ticket.ticket_types_description}: ${ticket.price}€`;
            //     ticketTypesContainer.appendChild(ticketElement);
            // });

        });
    });

    // Agregar evento de clic al botón de cierre para ocultar el menú emergente
    closePopupButton.addEventListener('click', function () {
        ticketTypesContainer.innerHTML = '';
        finalPrice.innerHTML = 'Total: 0.00€';
        totalTickets = 0;
        popupContainer.style.display = 'none';
    });

    // Cerrar el menú emergente al hacer clic en el fondo oscuro
    popupContainer.addEventListener('click', function (event) {
        if (event.target === popupContainer) {
            ticketTypesContainer.innerHTML = '';
            finalPrice.innerHTML = 'Total: 0.00€';
            totalTickets = 0;
            popupContainer.style.display = 'none';
        }
    });
}

const updateTotal = (priceChange) => {
    // Actualizar el total sumando o restando el cambio de precio
    totalTickets += priceChange;

    // Mostrar el total en el elemento con id 'final-price'
    finalPrice.textContent = `Total: ${totalTickets.toFixed(2)}€`;
}


