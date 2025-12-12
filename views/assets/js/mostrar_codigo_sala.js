document.addEventListener("DOMContentLoaded", () => {
  const url = window.location.href;
  const partes = url.split("/");
  const archivo = partes.pop();
  let mensaje = "";
  let codigo = localStorage.getItem("codigoSala");
  if (codigo) {
    document.getElementById("Btncodigo").innerHTML = `<i class="fa-solid fa-circle-info"></i>` + "  Código: " + codigo;
  } else {
    if(archivo == "balotas.php"){
      mensaje =
        "Debes primero iniciar un juego para generar las balotas del bingo, intentalo de nuevo";
    }else if(archivo == "jugadores.php"){
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
