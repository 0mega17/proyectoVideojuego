//funcion para habilitar el campo de nuevo password cuando se selecciona el checkbox
document
  .getElementById("cambiarPassword")
  .addEventListener("change", function () {
    const newPasswordField = document.getElementById("newPassword");

    if (this.checked) {
      newPasswordField.disabled = false;
    } else {
      newPasswordField.disabled = true;
      newPasswordField.value = ""; // Limpia el campo
    }
  });
// ACTUALIZAR DATOS DEL PERFIL
document.addEventListener("DOMContentLoaded", () => {
  const btnGuardar = document.getElementById("btnGuardar");

  if (btnGuardar) {
    btnGuardar.addEventListener("click", actualizar);
  }
});

function actualizar() {
  const nombre = document.getElementById("nombre").value;
  const email = document.getElementById("email").value;
  const oldPassword = document.getElementById("oldPassword").value;
  const newPassword = document.getElementById("newPassword").value;

  const formData = new FormData();
  formData.append("nombre", nombre);
  formData.append("email", email);
  formData.append("oldPassword", oldPassword);
  formData.append("newPassword", newPassword);

  fetch("../controllers/controllerEditarAdmin.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          icon: "success",
          title: `<h1 class="m-0 fw-bold">Exito! </h1>`,
          text: data.message,
          confirmButtonText: "Aceptar",
          customClass: {
            confirmButton: "btn btn-success fw-bold",
            cancelButton: "btn btn-danger fw-bold",
          },
        }).then(() => location.reload());
      } else {
        Swal.fire({
          icon: "error",
          title: `<h1 class="m-0 fw-bold">¡Error! </h1>`,
          text: data.message,
          confirmButtonText: "Entendido",
          customClass: {
            confirmButton: "btn btn-success fw-bold",
            cancelButton: "btn btn-danger fw-bold",
          },
        });
      }
    })
    .catch((error) => {
      console.error("Error de conexión:", error);
      Swal.fire({
        icon: "error",
        title: "Error de conexión",
        text: "No se pudo conectar con el servidor.",
      });
    });
}
