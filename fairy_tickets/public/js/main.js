// Asigna el tamaño de pantalla para el breakpoint
const screenBreakpoint = 768;
const mediaQuery = window.matchMedia(`(max-width: ${screenBreakpoint - 1}px)`);

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

// Si screen width < 768px, añade los listeners para el menú mobile
if (mediaQuery.matches) {
    toggleMobileMenu();

    // Function to close the menu if screen width surpasses a certain breakpoint
    const closeMenuOnResize = () => {
        const screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        const dropdownMenu = document.getElementById("nav-dropdown-menu");
        const menuToggle = document.getElementById("menu-toggle");

        // Mira si el menú está abierto y comprueba el tamaño de la pantalla
        if (dropdownMenu.classList.contains("opened") && screenWidth > screenBreakpoint) {
            dropdownMenu.classList.remove("opened");
            toggleIcon(menuToggle);
        }
    };

    // Event listener del evento 'resize' de la ventana
    window.addEventListener("resize", closeMenuOnResize);
}