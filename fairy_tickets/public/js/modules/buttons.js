import { findParentDialog } from "./utils.js";

// Función que añade un listener a todos los botones de cierre de los modales de la página
export const commonButtonsSetup = () => {
    const closeDialogBtns = document.querySelectorAll(".close-dialog-button");
    if (closeDialogBtns) {
        closeDialogBtns.forEach((btn) => {
            btn.addEventListener("click", (event) => {
                event.preventDefault();
                const dialog = findParentDialog(btn);
                dialog.close();
            });
        });
    }
};
