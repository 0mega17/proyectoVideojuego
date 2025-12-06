const btnBalota = document.querySelector("#btnBalota");
const btnReiniciar = document.querySelector("#btnReiniciar");
const tblBalotas = document.querySelector("#tblBalotas");
const datosSala = JSON.parse(localStorage.getItem("codigoSala"));
const divUltimaBalota = document.querySelector("#ultimaBalota");
console.log(datosSala);

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
    let tituloUltima = document.createElement("p");
    tituloUltima.classList.add("fw-bold", "my-2");
    tituloUltima.textContent = "Ultima balota";
    ultimaBalota.classList.add(
      "btn",
      "text-bg-info",
      "d-block",
      "w-100",
      "mb-3"
    );

    ultimaBalota.textContent = arregloBalotas[arregloBalotas.length - 1].balota;
    divUltimaBalota.appendChild(tituloUltima);
    divUltimaBalota.appendChild(ultimaBalota);
  }
});

btnBalota.addEventListener("click", () => {
  fetch("../controllers/generar_balota.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(arregloBalotas),
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
  console.log(datosSala);
  Swal.fire({
    title: `<h1 class="m-0">Reiniciar juego`,
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
      fetch("../controllers/reiniciar_juego.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(datosSala),
      })
        .then((res) => res.json())
        .then((response) => {
          if (response.success) {
            Swal.fire({
              title: "Exito",
              text: response.message,
              icon: "success",
            }).then(() => {
              localStorage.removeItem("navegadorBalotas");
              location.reload();
            });
          }
        });
    },
  });
});
