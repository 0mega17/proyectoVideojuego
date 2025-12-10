function cargarJugadores() {
  fetch("../controllers/controlador_listar_jugadores.php")
    .then((response) => response.text())
    .then((data) => {
      document.querySelector("#tblJugadores").innerHTML = data;
    })
    .catch((error) => console.error("Error:", error));
}

cargarJugadores();

setInterval(cargarJugadores, 3000);

document.addEventListener("click", function (e) {
  if (e.target.closest(".btnEliminar")) {
    const btn = e.target.closest(".btnEliminar");
    const ID = btn.dataset.id;

    Swal.fire({
      title: "Eliminar aprendiz",
      text: "¿Estás seguro de eliminar este aprendiz?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
    }).then(async (result) => {
      if (result.isConfirmed) {
        let formData = new FormData();
        formData.append("IDeliminar", ID);

        const request = await fetch(
          "../controllers/controlador_eliminar_jugador.php",
          {
            method: "POST",
            body: formData,
          }
        );

        const response = await request.json();

        if (response.success) {
          Swal.fire("Eliminado", response.message, "success");
          cargarJugadores();
        }
      }
    });
  }
});
