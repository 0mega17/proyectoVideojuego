const btnBalota = document.querySelector("#btnBalota");
const tblBalotas = document.querySelector("#tblBalotas");

tblBalotas.innerHTML = "";

let objetoBalotas = {};
let arregloBalotas = [];

document.addEventListener("DOMContentLoaded", () => {
  const datosGuardados = localStorage.getItem("navegadorBalotas");
  if(datosGuardados){
    arregloBalotas = JSON.parse(datosGuardados);
    pintarTabla(arregloBalotas);
  }
})


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
      arregloBalotas.push(objetoBalotas);
      localStorage.setItem("navegadorBalotas", JSON.stringify(arregloBalotas));
      Swal.fire({
        title: `<strong class="display-5">${res.columna}</strong>`,
        html: `${res.columna} : ${res.balota}`,
        icon: "success",
      }).then(() => {
         location.reload();
      });
    });
});


function pintarTabla(lista){
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
  })
}