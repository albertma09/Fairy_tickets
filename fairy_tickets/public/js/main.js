// Función debounce, recibe otra función y un tiempo de retraso
// Cuando recibe una llamada setea un timeout (el retraso)
// Si no se ha llegado a cumplir ese tiempo de retraso y recibe otra llamada,
// vuelve a setear el timeout a 0, así hasta que no reciba más llamadas
// en el período de tiempo indicado.
const debounce = (func, delay) => {
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

/* _______Navigation________ */
// Asigna el tamaño de pantalla para el breakpoint
const screenBreakpoint = 768;

// Arrow function que devuelve true si la ventana del usuario es más pequeña
// que el screen breakpoint que se ha indicado.
const checkScreenWidth = (screenBreakpoint) => {
    return window.matchMedia(`(max-width: ${screenBreakpoint - 1}px)`).matches;
};

// Función para cambiar el ícono del menú
const toggleIcon = (menuToggle) => {
    const originalIcon = menuToggle.querySelector("i");
    originalIcon.classList.toggle("fa-bars");
    originalIcon.classList.toggle("fa-bars-staggered");
};

// Arrow function para abrir y cerrar el menú en dispositivos móviles
const toggleMobileMenu = () => {
    const menuToggle = document.getElementById("menu-toggle");
    const dropdownMenu = document.getElementById("nav-dropdown-menu");

    // Abre y cierra el menú. Asignando y retirando la clase 'opened'
    menuToggle.addEventListener("click", function () {
        dropdownMenu.classList.toggle("opened");
        toggleIcon(menuToggle);
    });
};

const closeMenuOnResize = () => {
    const screenWidth =
        window.innerWidth ||
        document.documentElement.clientWidth ||
        document.body.clientWidth;
    const dropdownMenu = document.getElementById("nav-dropdown-menu");
    const menuToggle = document.getElementById("menu-toggle");

    // Mira si el menú está abierto y comprueba el tamaño de la pantalla
    if (
        dropdownMenu.classList.contains("opened") &&
        screenWidth > screenBreakpoint
    ) {
        dropdownMenu.classList.remove("opened");
        toggleIcon(menuToggle);
    }
};

// Función que comprueba si el width de la pantalla del usuario
// está por debajo del breakpoint, y si es así llama a toggleMobileMenu()
const addMenuFunctionalities = () => {
    if (checkScreenWidth(screenBreakpoint)) {
        toggleMobileMenu();
    }
};

// Llama a la función al cargar el documento
document.addEventListener("DOMContentLoaded", addMenuFunctionalities);

// Calcula cual es el tamaño al hacer resize:
// Si es > breakpoint cierra el menú
const delayedCloseMenuOnResize = debounce(() => {
    if (!checkScreenWidth(screenBreakpoint)) {
        closeMenuOnResize();
    }
}, 500);
// Event listener del evento 'resize' de la ventana
window.addEventListener("resize", delayedCloseMenuOnResize);

document.addEventListener("DOMContentLoaded", function () {
    // Obtén el elemento del enlace de cierre de sesión por su ID
    const logoutLink = document.getElementById("logout-link");

    // Agrega un event listener para el clic en el enlace
    if (logoutLink) {
        logoutLink.addEventListener("click", function (event) {
            // Previene el comportamiento predeterminado del enlace
            event.preventDefault();

            // Muestra una alerta de confirmación y, si el usuario acepta, envía el formulario de cierre de sesión
            let isConfirmed = confirm("¿Estás seguro de cerrar sesión?");

            if (isConfirmed) {
                document.getElementById("logout-form").submit();
            }
        });
    }
});
