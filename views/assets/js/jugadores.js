function cargarJugadores() {
  fetch("../controllers/controlador_listar_jugadores.php")
    .then((response) => response.json())
    .then((data) => {

      localStorage.setItem("listaJugadores", JSON.stringify(data));

      pintarTablaJugadores(data);
    })
    .catch((error) => console.error("Error:", error));
}

function pintarTablaJugadores(lista) {
  const tabla = document.querySelector("#tblJugadores");
  tabla.innerHTML = "";

  lista.forEach((jugador) => {
    const fila = document.createElement("tr");

    fila.innerHTML = `
      <td>${jugador.nombre}</td>
      <td>${jugador.ficha}</td>
      <td>
        <button class="btn btn-danger btn-sm btnEliminar" data-id="${jugador.id}">
            <i class="fa-solid fa-trash"></i> Eliminar
        </button>
      </td>
    `;

    tabla.appendChild(fila);
  });
}

document.addEventListener("DOMContentLoaded", () => {

  const guardado = localStorage.getItem("listaJugadores");

  if (guardado) {
    pintarTablaJugadores(JSON.parse(guardado));
  }


  cargarJugadores();


  let codigo = localStorage.getItem("codigoSala");
  if (codigo) {
    document.getElementById("Btncodigo").textContent = "Código: " + codigo;
  }
});

setInterval(cargarJugadores, 5000);

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
