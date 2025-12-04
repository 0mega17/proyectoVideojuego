const btnBalota = document.querySelector("#btnBalota");
const tblBalotas = document.querySelector("#tblBalotas");
let ancho = 0;

tblBalotas.innerHTML = "";

let objetoBalotas = {};
let arregloBalotas = [];

document.addEventListener("DOMContentLoaded", () => {
  const datosGuardados = localStorage.getItem("navegadorBalotas");
  if (datosGuardados) {
    arregloBalotas = JSON.parse(datosGuardados);
    pintarTabla(arregloBalotas);
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
      objetoBalotas = {
        columna: res.columna,
        balota: res.balota,
      };
      if (res.balota.length < 25) {
        ancho = 600;
      }

      if(res.balota.length > 25 && res.balota.length < 40){
        ancho = 900;
      }

      if (res.balota.length > 40) {
        ancho = 1400;
      }

      console.log(ancho);

      arregloBalotas.push(objetoBalotas);
      localStorage.setItem("navegadorBalotas", JSON.stringify(arregloBalotas));
      Swal.fire({
        html: `
    <div class="container-fluid py-4">

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
      }).then(() => {
        location.reload();
      });
    });
});

function pintarTabla(lista) {
  tblBalotas.innerHTML = "";

  lista.forEach((b) => {
    let fila = document.createElement("tr");

    let tdTipo = document.createElement("td");
    tdTipo.textContent = b.columna;

    let tdBalota = document.createElement("td");
    tdBalota.textContent = b.balota;

    fila.appendChild(tdTipo);
    fila.appendChild(tdBalota);

    tblBalotas.appendChild(fila);
  });
}
