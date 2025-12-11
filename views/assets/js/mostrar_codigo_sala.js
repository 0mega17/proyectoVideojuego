document.addEventListener("DOMContentLoaded", () => {
  let codigo = localStorage.getItem("codigoSala");
  if (codigo) {
    document.getElementById("Btncodigo").innerHTML = `<i class="fa-solid fa-circle-info"></i>` + "  Código: " + codigo;
  } else {
    Swal.fire({
      title: `<h1 class="m-0 fw-bold">Ocurrio un error... </h1`,
      text: "Debes crear primero una sala para generar las balotas del bingo, intentalo de nuevo ",
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
