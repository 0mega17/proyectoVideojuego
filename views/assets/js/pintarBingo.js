document.addEventListener("DOMContentLoaded", () => {
    const celdas = document.querySelectorAll("tbody td");

    celdas.forEach(td => {
      td.style.cursor = "pointer";

      td.addEventListener("click", () => {
        // Alternar clase "marcado"
        td.classList.toggle("marcado");
      });
    });
  });