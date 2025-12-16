let formularioLogin = document.getElementById("formularioLoginAprendiz");

formularioLogin.addEventListener("submit", (e) => {
  e.preventDefault();
  let datos = new FormData(formularioLogin);
  $.ajax({
    url: "../controllers/controlador_login_aprendices.php",
    type: "POST",
    data: datos,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (respuesta) {
      if (!respuesta.validacion) {
        Swal.fire({
          title: '<h1 class="m-0 fw-bold">¡Error! </h1>',
          text: respuesta.mensaje,
          icon: "error",
          confirmButtonText: "Aceptar",
          customClass: {
            confirmButton: "btn btn-success",
          },
        });
      } else {
        localStorage.clear();
        location.href = "./bingoTablas.php";
      }
    },
  });
});
