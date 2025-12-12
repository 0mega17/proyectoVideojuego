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

let botonIniciar = document.querySelector("#btnIniciar");
setInterval(cargarJugadores, 5000);
botonIniciar.addEventListener("click", () => {
  let sala = localStorage.getItem("codigoSala");
  Swal.fire({
    title: `<h1 class="m-0 fw-bold">Iniciar juego </h1>`,
    text: "¿Estás seguro de iniciar el juego?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, iniciar",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success fw-bold",
      cancelButton: "btn btn-danger fw-bold",
    },
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
        localStorage.setItem("iniciarJuego", true);
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
      title: `<h1 class="m-0 fw-bold">Eliminar</h1>`,
      text: "¿Estás seguro de eliminar este aprendiz?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "Cancelar",
      customClass: {
        confirmButton: "btn btn-success fw-bold",
        cancelButton: "btn btn-danger fw-bold",
      },
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
          Swal.fire({
            title: `<h1 class="m-0 fw-bold">Eliminado</h1>`,
            text: response.message,
            icon: "success",
            timer: 2000,
            allowOutsideClick: false,
            timerProgressBar: true,
            showConfirmButton: false,
            customClass: {
              confirmButton: "btn btn-success fw-bold",
            },
          });
          cargarJugadores();
        }
      }
    });
  }
});
