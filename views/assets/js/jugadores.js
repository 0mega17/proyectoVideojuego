let botonInicar = document.getElementById("btnIniciar");
function cargarJugadores() {
  fetch("../controllers/controlador_listar_jugadores.php")
    .then((response) => response.text())
    .then((data) => {
      document.querySelector("#tblJugadores").innerHTML = data;
    })
    .catch((error) => console.error("Error:", error));
}

cargarJugadores();
document.addEventListener("DOMContentLoaded", () => {
  let codigo = localStorage.getItem("codigoSala");
  if (codigo) {
    document.getElementById("Btncodigo").textContent = "Código: " + codigo;
  }
});

setInterval(cargarJugadores, 5000);
botonInicar.addEventListener("click", () => {
  let sala = localStorage.getItem("codigoSala");
  Swal.fire({
    title: "Iniciar juego",
    text: "¿Estás seguro de iniciar el juego?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, iniciar",
    cancelButtonText: "Cancelar",
  }).then(async (result) => {
    if (result.isConfirmed) {
      let formData = new FormData();
      formData.append("codigoSala", sala);

      const request = await fetch(
        "../controllers/controlador_iniciar_juego.php",
        {
          method: "POST",
          body: formData,
        }
      );
      const response = await request.json();
      if (response.success) {
        window.location.href = "balotas.php";
      }
    }
  });
});

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
