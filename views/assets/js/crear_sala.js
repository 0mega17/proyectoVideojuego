let formularioSala = document.getElementById("formularioSala");

formularioSala.addEventListener("submit", (e) => {
  e.preventDefault();
  let datos = new FormData(formularioSala);

  fetch("../controllers/controlador_crear_sala.php", {
    method: "POST",
    body: datos,
  })
    .then((res) => res.json())
    .then((res) => {
      if (res.success) {
        // GUARDAMOS TODO
        localStorage.setItem("codigoSala", res.sala);
        localStorage.setItem("modoJuego", res.modo);
        localStorage.setItem("categoria", res.categoria);

        Swal.fire({
          title: "CODIGO SALA",
          text: res.sala,
          icon: "info",
          confirmButtonText: "Entendido",
        }).then(() => {
          window.location.href = "balotas.php";
        });
      }
    });
});
