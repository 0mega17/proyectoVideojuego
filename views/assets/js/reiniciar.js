const txtCodigoSala = document.querySelector("#txtCodigoSala");
let ultimaFecha = null;
console.log(ultimaFecha);

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

      if (ultimaFecha !== data) {
        localStorage.clear();
        ultimaFecha = null;
        location.reload();
      }
    });
}, 3000);
