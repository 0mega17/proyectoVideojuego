function verificarSesion() {
    fetch("../controllers/validar_sesion_aprendiz.php")
    .then((response) => response.json())
    .then((data) => {
      if (!data.valido) {
        Swal.fire({
          icon: "error",
          title: "Sesión finalizada",
          text: "Tu sala ha sido cerrada o tu acceso ya no es válido.",
        }).then(() => {
          window.location.href = "login_usuarios.php";
        });
      }
    })
    .catch((err) => console.error("Error:", err));
}


setInterval(verificarSesion, 5000);


verificarSesion();
