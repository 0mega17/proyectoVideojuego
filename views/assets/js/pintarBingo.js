document.addEventListener("DOMContentLoaded", async () => {
  const celdas = document.querySelectorAll("tbody td");

  // 1. Revisar si ya existe una tabla guardada
  let tablaGuardada = JSON.parse(localStorage.getItem("bingoTabla"));

  if (!tablaGuardada) {
    // No existe → guardar la tabla actual generada por PHP
    tablaGuardada = [];
    celdas.forEach((celda) => tablaGuardada.push(celda.innerText));
    localStorage.setItem("bingoTabla", JSON.stringify(tablaGuardada));

    let formData = new FormData();
    formData.append("tablaGuardada", JSON.stringify(tablaGuardada));
    console.log(formData);

    const request = await fetch("../controllers/insertar_casillas.php", {
      method: "POST",
      body: formData,
    });

    const response = await request.json();
  } else {
    // Sí existe → reemplazar valores generados por PHP
    celdas.forEach((celda, i) => {
      celda.innerText = tablaGuardada[i];
    });
  }

  // ─────────── Guardar marcados ───────────
  const marcados = JSON.parse(localStorage.getItem("bingoMarcados")) || {};

  celdas.forEach((celda, index) => {
    // Restaurar marcado
    if (marcados[index]) {
      celda.classList.add("marcado");
    }

    // Evento de clic
    celda.addEventListener("click", () => {
      celda.classList.toggle("marcado");
      guardarMarcados();
    });
  });
});

// Guardar estados marcados
function guardarMarcados() {
  const celdas = document.querySelectorAll("tbody td");
  const estado = {};

  celdas.forEach((c, index) => {
    estado[index] = c.classList.contains("marcado");
  });

  localStorage.setItem("bingoMarcados", JSON.stringify(estado));
}
