const txtCodigoSala = document.querySelector("#txtCodigoSala");
let ultimaFecha = null;
let accion = localStorage.getItem("accion");

setInterval(() => {
  let formData = new FormData();
  formData.append("codigo", txtCodigoSala.value);
  fetch("../controllers/datos_estado_sala.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      console.log("Respuesta del servidor:", data); // <-- aquí

      if (!ultimaFecha) {
        ultimaFecha = data;
        console.log(ultimaFecha);
        return;
      }

      if (ultimaFecha !== data && accion == "reiniciar") {
        localStorage.clear();
        ultimaFecha = null;
        location.reload();
      }

      if (ultimaFecha !== data && accion == "finalizar") {
        localStorage.clear();
        ultimaFecha = null;
        fetch("../controllers/controlador_logout_aprendices.php")
          .then((res) => res.json())
          .then((data) => {
            if (data.success) {
              location.reload();
            }
          });
      }
    });
}, 3000);
