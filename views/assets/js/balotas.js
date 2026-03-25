const btnBalota = document.querySelector("#btnBalota");
const btnReiniciar = document.querySelector("#btnReiniciar");
const btnFinalizar = document.querySelector("#btnFinalizar");
const btnAutomatico = document.querySelector("#btnAutomatico");
const btnDetener = document.querySelector("#btnDetener");
const contenedorBalotas = document.querySelector("#contenedorBalotas");
// const tblBalotas = document.querySelector("#tblBalotas");
const datosSala = JSON.parse(localStorage.getItem("codigoSala"));
const btnCategoria = document.querySelector("#btnCategoria");
const btnJugadores = document.querySelector("#btnJugadores");
const categoria = localStorage.getItem("categoria");

let ancho = 0;
let intervalo;
let automatico = false;
// tblBalotas.innerHTML = "";

let objetoBalotas = {};
let arregloBalotas = [];

btnDetener.addEventListener("click", () => {
  const automaticoStorage = localStorage.getItem("automatico");
  if (automaticoStorage) {
    clearTimeout(intervalo);
    automatico = false;
    localStorage.setItem("automatico", automatico);
    Swal.fire({
      title: `<h1 class="mb-0 fw-bold">¡Exito!</h1>`,
      text: "El modo automatico se ha detenido",
      icon: "success",
      confirmButtonText: "Entendido",
      customClass: {
        confirmButton: "btn btn-success fw-bold",
      },
      timer: 2000,
      allowOutsideClick: false,
      timerProgressBar: true,
      showConfirmButton: false,
    }).then(() => {
      clearTimeout(intervalo);
      btnAutomatico.disabled = false;
      localStorage.setItem("automatico", false);
      location.reload();
    });
  }
});

btnAutomatico.addEventListener("click", () => {
  Swal.fire({
    title: `<h1 class="m-0 fw-bold">Modo Automatico </h1>`,
    text: "Las balotas se generaran cada 5 segundos",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, iniciar",
    cancelButtonText: "Cancelar",
    customClass: {
      confirmButton: "btn btn-success fw-bold",
      cancelButton: "btn btn-danger fw-bold",
    },
  }).then((result) => {
    if (result.isConfirmed) {
      automatico = true;
      localStorage.setItem("automatico", automatico);
      btnBalota.classList.add("disabled");
      btnAutomatico.disabled = true;
      setTimeout(() => {
        btnBalota.click();
      }, 3000);
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const nombreCategoria = localStorage.getItem("nombreCategoria");
  const cantidadJugadores = localStorage.getItem("cantidadJugadores");
  btnCategoria.innerHTML = nombreCategoria;
  btnJugadores.innerHTML = cantidadJugadores;

  const datosGuardados = localStorage.getItem("navegadorBalotas");
  const automaticoStorage = localStorage.getItem("automatico");

  if (datosGuardados) {
    arregloBalotas = JSON.parse(datosGuardados);
    pintarTabla(arregloBalotas);
  }

  if (automaticoStorage == "true") {
    btnAutomatico.disabled = true;
    btnBalota.classList.add("disabled");
    btnDetener.disabled = false;
    intervalo = setTimeout(() => {
      btnBalota.click();
    }, 4000);
  } else {
    btnBalota.classList.remove("disabled");
    btnDetener.disabled = true;
  }
});

// Listener para el filtro de balotas
const filtroInput = document.querySelector("#filtroBalotas");
filtroInput.addEventListener("input", () => {
  const filtro = filtroInput.value.toLowerCase();
  const filtradas = arregloBalotas.filter((b) =>
    b.balota.toLowerCase().includes(filtro),
  );
  pintarTabla(filtradas);
});

btnBalota.addEventListener("click", () => {
  let formData = new FormData();
  formData.append("arregloBalotas", JSON.stringify(arregloBalotas));
  formData.append("categoria", categoria);
  fetch("../controllers/generar_balota.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((res) => {
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
        const filtradas = arregloBalotas.filter((b) =>
          b.balota.toLowerCase().includes(filtro),
        );
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
              localStorage.setItem("reiniciando", "true");
              localStorage.removeItem("navegadorBalotas");
              localStorage.removeItem("automatico");
              localStorage.removeItem("accion");
              localStorage.removeItem("bingoTabla");
              localStorage.removeItem("bingoMarcados");
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
