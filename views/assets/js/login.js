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
          title: '<span class="fs-2 fw-bold"> ¡Error! </span>',
          text: respuesta.message,
          icon: "error",
        });
      } else {
        location.href = "./composiciones.php";
      }
    },
  });
});
