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

      if (
        ultimaFecha !== data.estado.updated_at &&
        accion == "finalizar"
      ) {
        localStorage.clear();
        ultimaFecha = null;
        fetch("../controllers/controlador_logout_aprendices.php")
          .then((res) => res.json())
          .then((response) => {
            if (response.success) {
              Swal.fire({
                title: `<h1 class="mb-0 fw-bold">Aviso!</h1>`,
                html: response.message,
                icon: "success",
                timer: 3000,
                allowOutsideClick: false,
                timerProgressBar: true,
                showConfirmButton: false,
                customClass: {
                  confirmButton: "btn btn-success fw-bold",
                },
              }).then(() => {
                location.reload();
              });
            }
          });
      }
    });
}, 3000);
