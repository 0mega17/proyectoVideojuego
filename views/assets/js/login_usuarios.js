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
          title: '<span class="fs-2 fw-bold"> ¡Error! </span>',
          text: respuesta.mensaje,
          icon: "error",
        });
      } else {
        location.href = "./bingoTablas.php";
      }
    },
  });
});
