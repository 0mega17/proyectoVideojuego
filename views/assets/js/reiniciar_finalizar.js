const txtCodigoSala = document.querySelector("#txtCodigoSala");
let ultimaFecha = null;
// let accion = localStorage.getItem("accion");
let accion = null;

setInterval(() => {
  let formData = new FormData();
  formData.append("codigo", txtCodigoSala.value);
  fetch("../controllers/datos_estado_sala.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      accion = data.estado.accion;
      if (!ultimaFecha) {
        ultimaFecha = data.estado.updated_at;
        return;
      }

      if (
        ultimaFecha !== data.estado.updated_at &&
        accion.includes("reiniciar")
      ) {
        localStorage.clear();
        ultimaFecha = null;
        location.reload();
      }
    });
}, 2000);
