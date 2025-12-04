document.addEventListener("DOMContentLoaded", () => {
  const modoJuego = document.getElementById("modoJuego");
  const categoriaSelect = document.getElementById("categoria");

  modoJuego.addEventListener("change", () => {
    const modo = modoJuego.value;

    if (modo === "categoria") {
      categoriaSelect.hidden = false;
      categoriaSelect.disabled = false;

      cargarCategorias();
    } else {
      categoriaSelect.hidden = true;
      categoriaSelect.disabled = true;
      categoriaSelect.innerHTML = `<option value="">Seleccione una categoría...</option>`;
    }
  });
});
function cargarCategorias() {
  fetch("../controllers/datos_categoria.php")
    .then((res) => res.json())
    .then((data) => {
      const categoriaSelect = document.getElementById("categoria");
      categoriaSelect.innerHTML = `<option value="">Seleccione una categoría...</option>`;

      data.forEach((cat) => {
        const opt = document.createElement("option");
        opt.value = cat.id;
        opt.textContent = cat.nombre;
        categoriaSelect.appendChild(opt);
      });
    })
    .catch((err) => {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Error al conectar con el servidor.",
      });
    });
}
