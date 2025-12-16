const btnBalota = document.querySelector("#btnBalota");
const btnReiniciar = document.querySelector("#btnReiniciar");
const btnFinalizar = document.querySelector("#btnFinalizar");
const contenedorBalotas = document.querySelector("#contenedorBalotas");
// const tblBalotas = document.querySelector("#tblBalotas");
const datosSala = JSON.parse(localStorage.getItem("codigoSala"));
const divUltimaBalota = document.querySelector("#ultimaBalota");
const btnCategoria = document.querySelector("#btnCategoria");
const btnJugadores = document.querySelector("#btnJugadores");
const categoria = localStorage.getItem("categoria");
console.log(categoria);

let ancho = 0;

// tblBalotas.innerHTML = "";

let objetoBalotas = {};
let arregloBalotas = [];

document.addEventListener("DOMContentLoaded", () => {
  const nombreCategoria = localStorage.getItem("nombreCategoria");
  const cantidadJugadores = localStorage.getItem("cantidadJugadores");
  btnCategoria.innerHTML =
    '<i class="fa-solid fa-list me-1"></i>' + "Categoria: " + nombreCategoria;
  btnJugadores.innerHTML =
    '<i class="fa-solid fa-users me-1"></i>' + "No. Jugadores: " + cantidadJugadores;

  const datosGuardados = localStorage.getItem("navegadorBalotas");
  if (datosGuardados) {
    arregloBalotas = JSON.parse(datosGuardados);
    pintarTabla(arregloBalotas);

    // Ultima balota
    let ultimaBalota = document.createElement("button");
    let tituloUltima = document.createElement("span");
    tituloUltima.classList.add("fw-bold");
    tituloUltima.textContent = "Ultima balota: ";
    ultimaBalota.classList.add(
      "btn",
      "text-bg-ultima-balota",
      "mb-2",
      "fs-5",
      "w-100",
      "p-3"
    );
    ultimaBalota.appendChild(tituloUltima);
    ultimaBalota.textContent +=
      arregloBalotas[0].balota;
    divUltimaBalota.appendChild(ultimaBalota);
  }
});

// Listener para el filtro de balotas
const filtroInput = document.querySelector("#filtroBalotas");
filtroInput.addEventListener("input", () => {
  const filtro = filtroInput.value.toLowerCase();
  const filtradas = arregloBalotas.filter(b => b.balota.toLowerCase().includes(filtro));
  pintarTabla(filtradas);
});

btnBalota.addEventListener("click", () => {
  let formData = new FormData();
  formData.append("arregloBalotas", JSON.stringify(arregloBalotas));
  formData.append("categoria", categoria);
  console.log(arregloBalotas);
  fetch("../controllers/generar_balota.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((res) => {
      console.log(res.tipo_obra);
      if (res.success) {
        objetoBalotas = {
          columna: res.columna,
          balota: res.balota,
          tipo_obra: res.tipo_obra,
          success: res.success,
        };
        arregloBalotas.unshift(objetoBalotas);
        
        // Aplicar filtro si existe
        const filtro = filtroInput.value.toLowerCase();
        const filtradas = arregloBalotas.filter(b => b.balota.toLowerCase().includes(filtro));
        pintarTabla(filtradas);
      }

      if (res.balota.length <= 25) {
        ancho = 600;
      }

      if (res.balota.length > 25 && res.balota.length <= 45) {
        ancho = 900;
      }

      if (res.balota.length > 45) {
        ancho = 1400;
      }

      localStorage.setItem("navegadorBalotas", JSON.stringify(arregloBalotas));
      Swal.fire({
        html: `
    <div class="py-4">

      <div class="row justify-content-center align-items-center">

        <div class="d-flex justify-content-center mb-5 mb-md-0">
          <div class="bolaBingo"></div>
        </div>

        <div class="text-center">
          <h1 class="display-1 fw-bold">${res.columna}</h1>
          <p class="text-muted display-2 m-0">${res.balota}</p>
        </div>

      </div>

    </div>
  `,
        width: ancho,
        confirmButtonText: "Aceptar",
        customClass: {
          confirmButton: "text-center btn btn-success fw-bold fs-5 w-100",
        },
        timer: 6000,
        allowOutsideClick: false,
        timerProgressBar: true,
        showConfirmButton: false,
      }).then(() => {
        location.reload();
      });
    });
});

function pintarTabla(lista) {
  contenedorBalotas.innerHTML = "";

  lista.forEach((b) => {
    const balota = document.createElement("div");
    balota.classList.add("balota");

    balota.innerHTML = `
      <div>
        <div class="columna">${b.columna}</div>
        <div class="texto">${b.balota}</div>
        <div class="tipo">${b.tipo_obra}</div>
      </div>
    `;

    contenedorBalotas.appendChild(balota);
  });
}


btnReiniciar.addEventListener("click", () => {
  let accion = "reiniciar";
  localStorage.setItem("accion", accion);
  Swal.fire({
    title: `<h1 class="m-0 fw-bold">Reiniciar </h1>`,
    html: "¿Esta seguro de realizar esta acción?",
    icon: "warning",
    confirmButtonText: "Si, reiniciar juego",
    cancelButtonText: "Cancelar",
    showCancelButton: true,
    customClass: {
      confirmButton: "btn btn-success fw-bold",
      cancelButton: "btn btn-danger fw-bold",
    },
    preConfirm: () => {
      let formData = new FormData();
      formData.append("codigoSala", datosSala);
      fetch("../controllers/reiniciar_juego.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((response) => {
          if (response.success) {
            Swal.fire({
              title: `<h1 class="mb-0 fw-bold">¡Exito!</h1>`,
              text: response.message,
              icon: "success",
              confirmButtonText: "Continuar juego",
              customClass: {
                confirmButton: "btn btn-success fw-bold",
              },
            }).then(() => {
              localStorage.removeItem("navegadorBalotas");
              location.reload();
            });
          }
        });
    },
  });
});

btnFinalizar.addEventListener("click", () => {
  let accion = "finalizar";
  localStorage.setItem("accion", accion);
  Swal.fire({
    title: `<h1 class="m-0 fw-bold">Finalizar </h1>`,
    html: "¿Esta seguro de realizar esta acción?",
    icon: "error",
    confirmButtonText: "Si, finalizar juego",
    cancelButtonText: "Cancelar",
    showCancelButton: true,
    customClass: {
      confirmButton: "btn btn-success fw-bold",
      cancelButton: "btn btn-danger fw-bold",
    },
    preConfirm: () => {
      let formData = new FormData();
      formData.append("codigoSala", datosSala);
      fetch("../controllers/finalizar_juego.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((response) => {
          if (response.success) {
            Swal.fire({
              title: `<h1 class="mb-0 fw-bold">¡Exito!</h1>`,
              text: response.message,
              icon: "success",
              confirmButtonText: "Finalizado",
              customClass: {
                confirmButton: "btn btn-success fw-bold",
              },
            }).then(() => {
              localStorage.clear();
              location.reload();
              location.href = "./sala.php";
            });
          }
        });
    },
  });
});
