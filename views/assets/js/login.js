let formularioLogin = document.getElementById("formularioLogin");

formularioLogin.addEventListener("submit", (e) => {
  e.preventDefault();
  let datos = new FormData(formularioLogin);
  $.ajax({
    url: "../controllers/controlador_login.php",
    type: "POST",
    data: datos,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (respuesta) {
      if (!respuesta.success) {
        Swal.fire({
          title: '<h1 class="m-0 fw-bold">¡Error! </h1>',
          text: respuesta.message,
          icon: "error",
          confirmButtonText: "Aceptar",
          customClass: {
            confirmButton: "btn btn-success",
          },
        });
      } else {
        location.href = "./sala.php";
      }
    },
  });
});
