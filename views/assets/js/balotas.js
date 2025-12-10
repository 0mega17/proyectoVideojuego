const btnBalota = document.querySelector("#btnBalota");
const btnReiniciar = document.querySelector("#btnReiniciar");
const btnFinalizar = document.querySelector("#btnFinalizar");
const tblBalotas = document.querySelector("#tblBalotas");
const datosSala = JSON.parse(localStorage.getItem("codigoSala"));
const divUltimaBalota = document.querySelector("#ultimaBalota");
const categoria = localStorage.getItem("categoria");
console.log(categoria);

let ancho = 0;

tblBalotas.innerHTML = "";

let objetoBalotas = {};
let arregloBalotas = [];

document.addEventListener("DOMContentLoaded", () => {
  const datosGuardados = localStorage.getItem("navegadorBalotas");
  if (datosGuardados) {
    arregloBalotas = JSON.parse(datosGuardados);
    pintarTabla(arregloBalotas);

    // Ultima balota
    let ultimaBalota = document.createElement("button");
    let tituloUltima = document.createElement("span");
    tituloUltima.classList.add("fw-bold");
    tituloUltima.textContent = "Ultima balota: ";
    ultimaBalota.classList.add("btn", "text-bg-info", "mb-2", "fs-5", "w-100");
    ultimaBalota.appendChild(tituloUltima);
    ultimaBalota.textContent += arregloBalotas[arregloBalotas.length - 1].balota;
    divUltimaBalota.appendChild(ultimaBalota);
  }
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
        arregloBalotas.push(objetoBalotas);
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
  tblBalotas.innerHTML = "";

  lista.forEach((b) => {
    let fila = document.createElement("tr");

    let tdCategoria = document.createElement("td");
    tdCategoria.textContent = b.columna;

    let tdBalota = document.createElement("td");
    tdBalota.textContent = b.balota;

    let tdTipoObra = document.createElement("td");
    tdTipoObra.textContent = b.tipo_obra;

    fila.appendChild(tdCategoria);
    fila.appendChild(tdBalota);
    fila.appendChild(tdTipoObra);

    tblBalotas.appendChild(fila);
  });
}

btnReiniciar.addEventListener("click", () => {
  let accion = "reiniciar";
  localStorage.setItem("accion", accion);
  Swal.fire({
    title: `<h1 class="m-0 fw-bold">Reiniciar </h1>`,
    html: "¿Esta seguro de realizar esta acción?",
    icon: "info",
    confirmButtonText: "Si, reiniciar juego",
    cancelButtonText: "Cancelar",
    showCancelButton: true,
    customClass: {
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
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
      confirmButton: "btn btn-success",
      cancelButton: "btn btn-danger",
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
