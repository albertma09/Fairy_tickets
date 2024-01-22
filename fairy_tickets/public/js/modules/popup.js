

export const openModal = () => {
    const popupContainer = document.querySelector('.popup-container');
    const closePopupButton = document.querySelector('.close-popup');
    const ticketTypesContainer = document.getElementById('ticket-types-container');

    // Agregar evento de clic al botón "Comprar" para mostrar el menú emergente
    document.querySelectorAll('.sesion-card .button-brand').forEach(function (button) {
        button.addEventListener('click', function () {
            // Mostrar el menú emergente
            popupContainer.style.display = 'flex';

            // Obtener el ID de la sesión desde el botón
            const sessionId = button.id;

            console.log(tickets);

            Object.entries(tickets).forEach(([ticketId, ticket]) => {

                if (ticket.session_id==button.id){
                console.log(`ID del ticket: ${ticketId}`);
                console.log(`Sesión ID: ${ticket.session_id}`);
                console.log(`Precio: ${ticket.price}€`);
                console.log(`Descripción: ${ticket.ticket_types_description}`);
                console.log('---'); // Separador para claridad

                const ticketElement = document.createElement('p');
                ticketElement.textContent = `Sesión ID: ${ticket.session_id}, Precio: ${ticket.price}€, Descripción: ${ticket.ticket_types_description}`;
                ticketTypesContainer.appendChild(ticketElement);
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

        popupContainer.style.display = 'none';
    });

    // Cerrar el menú emergente al hacer clic en el fondo oscuro
    popupContainer.addEventListener('click', function (event) {
        if (event.target === popupContainer) {
            ticketTypesContainer.innerHTML = '';

            popupContainer.style.display = 'none';
        }
    });
}

