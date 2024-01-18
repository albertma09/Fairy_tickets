// Función debounce, recibe otra función y un tiempo de retraso
// Cuando recibe una llamada setea un timeout (el retraso)
// Si no se ha llegado a cumplir ese tiempo de retraso y recibe otra llamada,
// vuelve a setear el timeout a 0, así hasta que no reciba más llamadas
// en el período de tiempo indicado.
export const debounce = (func, delay) => {
    let timeout;

    return function () {
        const context = this;
        const args = arguments;

        // aquí se resetea el timeout si ya existía
        clearTimeout(timeout);

        timeout = setTimeout(() => {
            func.apply(context, args);
        }, delay);
    };
};

// Función que recibe un elemento y encuentra su dialogo padre, si lo tiene
export const findParentDialog = (element) => {
    let parent = element.parentNode;
  
    while (parent && parent.tagName !== 'DIALOG') {
      parent = parent.parentNode;
    }
  
    return parent; // Devuelve el tag del dialog o null
  }