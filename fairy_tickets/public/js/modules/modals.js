let totalTickets = 0;
let totalTicketsSelected = 0;
const totalPrice = document.querySelector('#totalPrice');
const confirmPayButtom = document.querySelector('#confirmPayButtom');
const buttonSession = document.querySelectorAll('button[name="session-buy"]');
const finalPrice = document.querySelector('#final-price');

//variables confirmar pago
const totalInfoTickets = document.querySelector('.sesiones-container');
const containers = document.querySelectorAll('#ticket-types-container');
const ticketID = document.querySelector('#ticketTId');
let nodes = [];
let showPrice;
let valueToArray;

//Función que reinicia el conteo del total de tickets seleccionados para la compra
const resetTotalTicketsSelected = (inputValues) => {
    totalTicketsSelected = 0;
    inputValues.forEach(element => {
        totalTicketsSelected += parseInt(element.value);
    });
}

//Función que activa el boton "confirmar compra" siempre y cuando la cantidad de total de tickets sea mayor a cero.
const activateButtonConfirm = () => {
    buttonSession.forEach(button => {
        button.addEventListener('click', function () {
            const inputsNumberTickets = document.querySelectorAll('input[name="numbersTickets"]');
            inputsNumberTickets.forEach(element => {
                element.addEventListener('blur', function () {
                    resetTotalTicketsSelected(inputsNumberTickets);
                    // console.log(totalTicketsSelected);
                    if (totalTicketsSelected > 0) {
                        confirmPayButtom.removeAttribute('disabled');
                    } else {
                        confirmPayButtom.removeAttribute('disabled');
                        confirmPayButtom.setAttribute('disabled', '');
                    }
                });
            });
        })
    });
}

const updateTotal = (priceChange, finalPrice) => {

    // Actualizar el total sumando o restando el cambio de precio
    totalTickets += priceChange;
    if (totalTickets > 0) {
        showPrice = totalTickets.toFixed(2)
        totalPrice.setAttribute('value', ((totalTickets.toFixed(2)).toString()).replace(/\./g, ''));
    }
    if (totalTickets > 0) {
        showPrice = totalTickets.toFixed(2)
        totalPrice.setAttribute('value', ((totalTickets.toFixed(2)).toString()).replace(/\./g, ''));
    }
    // Mostrar el total en el elemento con id 'final-price'
    finalPrice.textContent = `Total: ${totalTickets.toFixed(2)}€`;


};

const obtainDataToSummary = () => {
    if (totalInfoTickets != null) {
        totalInfoTickets.addEventListener("click", function () {
            confirmPayButtom.addEventListener("click", function (e) {
                // e.preventDefault();
                localStorage.clear();
                containers.forEach(container => {
                    container.childNodes.forEach(conta => {
                        nodes.push(conta.id);
                        conta.childNodes.forEach(element => {
                            element.childNodes.forEach(elem => {
                                if (elem.tagName === 'INPUT') {
                                    valueToArray = elem.value;
                                }
                                else {
                                    valueToArray = elem.innerText;
                                }
                                nodes.push(valueToArray);
                            })
                        })
                    });
                    ticketID.setAttribute('value', nodes[0]);
                    activateButtonConfirm();
                    localStorage.setItem("totalPrice", showPrice);
                    localStorage.setItem("dataPurchase", nodes);
                    localStorage.setItem('event_id', tickets[0].event_id);
                });
            })
        })
    }
}


const buildBuyContainer = (ticket, buyContainer, finalPrice) => {
    const numberOfTickets = document.createElement("input");
    numberOfTickets.setAttribute('name', 'numbersTickets');//
    numberOfTickets.setAttribute('name', 'numbersTickets');//
    numberOfTickets.size = 1;
    const addQuantityText = document.createElement("p");
    addQuantityText.textContent = 'Ingresa la cantidad';

    numberOfTickets.value = "0";
    let selectedQuantity = 0;

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
            quantity = parseInt(numberOfTickets.value);





        };
    };

    numberOfTickets.addEventListener("blur", calculateTotal(selectedQuantity));




    buyContainer.appendChild(numberOfTickets);
    buyContainer.appendChild(addQuantityText);
};

const buildInfoContainer = (ticket, informationContainer) => {
    const ticketPrice = document.createElement("p");
    const ticketDescription = document.createElement("p");



    ticketPrice.textContent = `${ticket.price}€`;
    ticketPrice.setAttribute('name', 'price');//
    ticketPrice.setAttribute('name', 'price');//
    ticketDescription.textContent = `${ticket.ticket_types_description}`;
    ticketDescription.setAttribute('name', 'ticket_name');//
    ticketDescription.setAttribute('name', 'ticket_name');//

    informationContainer.appendChild(ticketPrice);
    informationContainer.appendChild(ticketDescription);
};

const buildTicketContainer = (ticket, ticketTypesContainer, finalPrice) => {
    const ticketContainer = document.createElement("div");
    const informationContainer = document.createElement("div");
    const buyContainer = document.createElement("div");

    ticketContainer.classList.add("ticket-container");
    ticketContainer.setAttribute('id', `${ticket.id}`);// 
    ticketContainer.setAttribute('id', `${ticket.id}`);// 
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
    totalTicketsSelected = 0;
    if (totalTicketsSelected === 0) {
        confirmPayButtom.removeAttribute('disabled');
        confirmPayButtom.setAttribute('disabled', '');
    }
    totalTicketsSelected = 0;
    if (totalTicketsSelected === 0) {
        confirmPayButtom.removeAttribute('disabled');
        confirmPayButtom.setAttribute('disabled', '');
    }
};


const ticketSalesModalSetup = () => {

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
    obtainDataToSummary();//
};

// Snippet que carga los modales de feedback en todas las páginas
const feedbackDialog = document.querySelector('dialog.fb-dialog');
if (feedbackDialog) {
    feedbackDialog.showModal()
}

export const logoutModal = () => {
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
}

export const closeSaleModal = () => {
    const closeSales = document.querySelectorAll(".closeSale");
    const closeSaleDialog = document.getElementById('closeSaleDialog');
    const cancelButton = document.getElementById('cancelButtonSale');
    const confirmButton = document.getElementById('confirmButtonSale');
    let id;
    // Agrega un event listener para el clic en cada botón de cierre
    closeSales.forEach(closeSale => {
        closeSale.addEventListener("click", function (event) {
            id = closeSale.getAttribute('id');
            closeSaleDialog.showModal();
        });
        
    });

    cancelButton.addEventListener('click', () => {
        closeSaleDialog.close();
    });

    confirmButton.addEventListener('click', () => {
        console.log(id);
        document.getElementById('close-sale-form-'+id).submit();
        closeSaleDialog.close();
    });
}

export { ticketSalesModalSetup, activateButtonConfirm };