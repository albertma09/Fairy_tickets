/* Nav - funcionalidades */
// Arrow function, recibe el botón toggle del menú del navegador en modo mobile.
// Mira si el icono es el inicial y si lo es lo cambia por el icono del menú abierto
const toggleIcon = (menuToggle) => {
   const originalIcon = menuToggle.querySelector("i");
   if (originalIcon.classList.contains("fa-bars")) {
       originalIcon.classList.remove("fa-bars");
       originalIcon.classList.add("fa-bars-staggered");
   } else if (originalIcon.classList.contains("fa-bars-staggered")) {
       originalIcon.classList.remove("fa-bars-staggered");
       originalIcon.classList.add("fa-bars");
   }
};

// Abre y cierra el menú. Asignando y retirando la clase 'opened'
const menuToggle = document.getElementById("menu-toggle");
const dropdownMenu = document.getElementById("nav-dropdown-menu");
menuToggle.addEventListener("click", function () {
   dropdownMenu.classList.toggle("opened");
   toggleIcon(menuToggle);
});