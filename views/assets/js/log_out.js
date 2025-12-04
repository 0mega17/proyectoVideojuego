console.log("log_out.js cargado");
let btnSalir = document.getElementById("btnSalir");
btnSalir.addEventListener("click", () => {
  window.location.href = "../controllers/controlador_logout.php";
});
