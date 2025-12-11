function verificarSesion() {
  fetch("../controllers/validar_sesion_aprendiz.php")
    .then((response) => response.json())
    .then((data) => {
      if (!data.valido) {
        Swal.fire({
          title: `<h1 class="fw-bold m-0">Sesión finalizada </h1>`,
          text: "Tu sala ha sido cerrada o tu acceso ya no es válido.",
          icon: "error",
          timer: 3000,
          allowOutsideClick: false,
          timerProgressBar: true,
          showConfirmButton: false,
        }).then(() => {
          window.location.href = "login_usuarios.php";
        });
      }
    })
    .catch((err) => console.error("Error:", err));
}

setInterval(verificarSesion, 5000);

verificarSesion();
