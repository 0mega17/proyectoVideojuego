document.addEventListener("DOMContentLoaded", () => {
  const url = window.location.href;
  const partes = url.split("/");
  const archivo = partes.pop();
  let mensaje = "";
  let codigo = localStorage.getItem("codigoSala");
  let cantidadJugadores = localStorage.getItem("cantidadJugadores");

  // Validacion para balotas en caso de se no se inicie el juego
  let iniciarJuego = localStorage.getItem("iniciarJuego");
  if (iniciarJuego == "false" && archivo == "balotas.php") {
    Swal.fire({
      title: `<h1 class="m-0 fw-bold">Ocurrio un error... </h1`,
      text: "Debes primero iniciar un juego para generar las balotas del bingo, intentalo de nuevo",
      icon: "error",
      confirmButtonText: "Entendido",
      customClass: {
        confirmButton: "btn btn-success",
      },
    }).then(() => {
      window.location.href = "./jugadores.php";
    });
  }

  // Validacion para jugadores en caso que no se haya generado la sala
  if (codigo) {
    document.getElementById("Btncodigo").innerHTML = codigo;
    document.getElementById("btnJugadores").innerHTML = cantidadJugadores;
  } else {
    if (archivo == "balotas.php") {
      mensaje =
        "Debes primero iniciar un juego para generar las balotas del bingo, intentalo de nuevo";
    } else if (archivo == "jugadores.php") {
      mensaje =
        "Debes crear primero una sala para visualizar a los jugadores, intentalo de nuevo";
    }
    Swal.fire({
      title: `<h1 class="m-0 fw-bold">Ocurrio un error... </h1`,
      text: mensaje,
      icon: "error",
      confirmButtonText: "Entendido",
      customClass: {
        confirmButton: "btn btn-success",
      },
    }).then(() => {
      window.location.href = "./sala.php";
    });
  }
});
