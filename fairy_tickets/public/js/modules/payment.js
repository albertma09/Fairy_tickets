const showPrice = parseFloat(localStorage.getItem("totalPrice"));//
let arrayData = [];
const summaryTable = document.querySelector('#summaryPurchase');
const price = document.createElement('div');
const labelPrice = document.createElement('div');
const generalForm = document.querySelector('.contentFields');
const assistantForm = document.querySelector('#templateForm');
const ticketsIdOwner = document.querySelector('#ticketsIdOwner');
const ticketsQuantityOwner = document.querySelector('#ticketsQuantityOwner');
const priceToRedsys = document.querySelector('input[name="priceToRedsys"]');
const event_id = localStorage.getItem('event_id');
const tiempoInactividad = 600000;
let tiempoSinActividad = 0;
let arrayjson = [];

let quantityTickets = 0;
let dataquantityTickets = [];
let ticketsTypeId = 0;
let dataTicketsTypeId = [];

//constantes para validacion del comprador
const nameOwner = document.querySelector('#nameOwner');
const dniOwner = document.querySelector('#dniOwner');
const emailOwner = document.querySelector('#emailOwner');
const mobileOwner = document.querySelector('#mobileOwner');

// Función que convierte el dato del localStorage en un array para mostrar los datos de la compra 
const convertToArray = () => {
    if (localStorage.getItem('dataPurchase') != null) {
        let dataPurchase = localStorage.getItem('dataPurchase').split(',');
        let tempArray = [];
        for (let i = 0; i < dataPurchase.length; i++) {
            if (dataPurchase[i] !== "Ingresa la cantidad") {
                tempArray.push(dataPurchase[i]);
            } else {
                arrayData.push(tempArray);
                tempArray = [];
            }
        }
    }
}

// Función que realiza la inserción de los datos en el recuadro del resumen de la compra
const insertDataSummaryPurchase = () => {
    convertToArray();
    const rowDataTotal = document.createElement('div');
    for (let i = 0; i < arrayData.length; i++) {
        let jsonTickets = {};
        if (parseInt(arrayData[i][3]) > 0) {
            const rowData = document.createElement('div');
            const cellDataName = document.createElement('div');
            const cellDataNumber = document.createElement('div');
            cellDataName.textContent = arrayData[i][2];
            cellDataNumber.textContent = `${arrayData[i][3]}  x  ${arrayData[i][1]}`;
            cellDataNumber.classList.add('summary-content-purchase-amount');
            rowData.appendChild(cellDataName);
            rowData.appendChild(cellDataNumber);
            rowData.classList.add('summary-content-purchase');
            summaryTable.appendChild(rowData);

            //datos de los tickets para la BD
            jsonTickets.ticket_name = `${arrayData[i][2]}`;
            jsonTickets.ticket_id = `${arrayData[i][0]}`;
            jsonTickets.number_tickets = `${arrayData[i][3]}`;
            arrayjson.push(jsonTickets);

            //datos para las compras no nominales
            ticketsTypeId = parseInt(arrayData[i][0]);
            dataTicketsTypeId.push(ticketsTypeId);
            quantityTickets = parseInt(arrayData[i][3]);
            dataquantityTickets.push(quantityTickets);
        }

    }
    labelPrice.textContent = "Total";
    labelPrice.classList.add('titulo-seccion');
    price.textContent = `${showPrice.toFixed(2)} €`;
    price.classList.add('titulo-seccion');
    rowDataTotal.appendChild(labelPrice);
    rowDataTotal.appendChild(price);
    rowDataTotal.classList.add('summary-content-purchase');
    summaryTable.appendChild(rowDataTotal);
    priceToRedsys.value = ((showPrice.toFixed(2)).toString()).replace(/\./g, '');
    localStorage.removeItem('dataPurchase');
}

//Función que cuenta la cantidad de tickets seleccionados para generar los formularios de los tickets nominales.
const createFormsAssistantCopies = () => {
    if (assistantForm != null) {
        arrayjson.forEach(element => {
            const ticketTitle = document.createElement('div');
            ticketTitle.textContent = `${element['ticket_name']}`;
            ticketTitle.classList.add('titulo-subtitulo');
            generalForm.appendChild(ticketTitle);
            for (let i = 0; i < parseInt(element['number_tickets']); i++) {
                const copyContainerForm = assistantForm.content.cloneNode('true');
                copyContainerForm.querySelector(".titulo-seccion-asistente").textContent = `Asistente # ${i + 1}`;
                copyContainerForm.querySelector('label').setAttribute('for', `nameAssistant${i + 1}`);
                copyContainerForm.querySelector('input').setAttribute('id', `nameAssistant${i + 1}`);
                copyContainerForm.querySelector('label[for="dniAssistant"]').setAttribute('for', `dniAssistant${i + 1}`);
                copyContainerForm.querySelector('#dniAssistant').setAttribute('id', `dniAssistant${i + 1}`);
                copyContainerForm.querySelector('label[for="mobileAssistant"]').setAttribute('for', `mobileAssistant${i + 1}`);
                copyContainerForm.querySelector('#mobileAssistant').setAttribute('id', `mobileAssistant${i + 1}`);
                copyContainerForm.querySelector('#ticket').setAttribute('value', `${element['ticket_id']}`);
                generalForm.appendChild(copyContainerForm);
            }
        });
    }else{
        ticketsIdOwner.setAttribute('value',dataTicketsTypeId);
        ticketsQuantityOwner.setAttribute('value',dataquantityTickets);
    }
}

// Funciones para actualizar el tiempo sin actividad
const actualizarTiempo = () => {
    tiempoSinActividad = 0;
};

function detectarInactividad(tiempo) {
    let tiempoSinActividad = 0;
    // console.log(event_id);

    actualizarTiempo();

    // Registrar eventos que reinician el tiempo sin actividad
    document.addEventListener("mousemove", actualizarTiempo);
    document.addEventListener("keydown", actualizarTiempo);
    document.addEventListener("click", actualizarTiempo);

    // Intervalo para verificar la inactividad
    setInterval(() => {
        tiempoSinActividad += 1000; // Aumentar el tiempo sin actividad en 1 segundo

        if (tiempoSinActividad >= tiempo) {
            // Redirigir a la página después del tiempo de inactividad
            window.location.href = `http://${location.host}/detalles-evento/${localStorage.getItem('event_id')}`;
            localStorage.removeItem('event_id');
        }
    }, 1000); // Revisar cada segundo
}

const summaryPurchaseInfo = () => {
    insertDataSummaryPurchase();
    createFormsAssistantCopies();
    localStorage.removeItem('totalPrice');
    localStorage.removeItem('dataPurchase');
}

summaryPurchaseInfo();
detectarInactividad(tiempoInactividad);