document.addEventListener("DOMContentLoaded", () => {
  let codigo = localStorage.getItem("codigoSala");
  if (codigo) {
    document.getElementById("Btncodigo").textContent = "Código: " + codigo;
  }
});
