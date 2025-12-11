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
        // Eliminamos el localstorage que exista
        localStorage.clear();
        // GUARDAMOS TODO LO NUEVO
        localStorage.setItem("codigoSala", res.sala);
        localStorage.setItem("modoJuego", res.modo);
        localStorage.setItem("categoria", res.categoria);
        localStorage.setItem("nombreCategoria", res.nombreCategoria);

        Swal.fire({
          title: `<h1 class="m-0 fw-bold">Codigo sala </h1>`,
          html: `<h2 class="m-0">${res.sala}</h2>`,
          icon: "info",
          confirmButtonText: "Aceptar",
          customClass: {
            confirmButton: "text-center btn btn-success fw-bold fs-5 w-100",
          },
        }).then(() => {
          window.location.href = "jugadores.php";
        });
      }else{
         Swal.fire({
           title: `<h1 class="m-0 fw-bold"> ¡Error! </h1>`,
           html: res.message,
           icon: "error",
           confirmButtonText: "Aceptar",
           customClass: {
             confirmButton: "text-center btn btn-success fw-bold fs-5 w-100",
           },
         });
      }
    });
});
