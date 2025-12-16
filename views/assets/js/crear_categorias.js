const modalCategorias = new bootstrap.Modal("#modalCategorias");
const btnAgregar = document.querySelector("#btnAgregar");
const tblCategorias = document.querySelector("#tblCategorias");
const frmCategorias = document.querySelector("#frmCategorias");
const txtCategoria = document.querySelector("#txtCategoria");
let modoEdicion = false;
let IDeditar = 0;
let IDeliminar = 0;
btnAgregar.addEventListener("click", () => {
  modoEdicion = false;
  IDeditar = 0;
  frmCategorias.reset();
  modalCategorias.show();
});
tblCategorias.addEventListener("click", async (e) => {
  if (e.target.classList.contains("btnEditar")) {
    modoEdicion = true;
    IDeditar = e.target.dataset.id;
    let formData = new FormData();
    formData.append("IDeditar", IDeditar);
    const request = await fetch("../controllers/controlador_categorias.php", {
      method: "POST",
      body: formData,
    });
    const response = await request.json();
    console.log("RESPUESTA EDITAR:", response);
    txtCategoria.value = response.datos.nombre;
    modalCategorias.show();
  }
  if (e.target.classList.contains("btnEliminar")) {
    IDeliminar = e.target.dataset.id;
    let nombre = e.target.dataset.nombre;
    console.log("DATASET ELIMINAR:", e.target.dataset);
    Swal.fire({
      title: `<h1 class="m-0 fw-bold">Eliminar</h1>`,
      html: `¿Está seguro de eliminar esta categoría?<br>
            <strong>${nombre}</strong>`,
      icon: "warning",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonText: "Sí, eliminar",
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger",
      },
      preConfirm: async () => {
        let formData = new FormData();
        formData.append("IDeliminar", IDeliminar);
        const request = await fetch("../controllers/eliminar_categoria.php", {
          method: "POST",
          body: formData,
        });
        const response = await request.json();
        if (response.success) {
          Swal.fire({
            title: `<h1 class="m-0 fw-bold">¡Exito!</h1>`,
            text: response.message,
            icon: "success",
            timer: 2000,
            showConfirmButton: false,
          }).then(() => location.reload());
        } else {
          Swal.fire({
            title: `<h1 class="m-0 fw-bold">¡Error!</h1>`,
            text: response.message,
            icon: "error",
            timer: 2000,
            showConfirmButton: false,
          });
        }
      },
    });
  }
});
frmCategorias.addEventListener("submit", async (e) => {
  e.preventDefault();
  modalCategorias.hide();
  const url = modoEdicion
    ? "../controllers/editar_categoria.php"
    : "../controllers/controlador_crear_categoria.php";
  const formData = new FormData(frmCategorias);
  if (modoEdicion && IDeditar > 0) {
    formData.append("IDeditar", IDeditar);
  }
  const request = await fetch(url, {
    method: "POST",
    body: formData,
  });
  const response = await request.json();
  if (response.success) {
    Swal.fire({
      title: `<h1 class="m-0 fw-bold">¡Exito!</h1>`,
      text: response.message,
      icon: "success",
      timer: 2000,
      showConfirmButton: false,
    }).then(() => location.reload());
  } else {
    Swal.fire({
      title: `<h1 class="m-0 fw-bold">¡Error!</h1>`,
      text: response.message,
      icon: "error",
      timer: 2000,
      showConfirmButton: false,
    }).then(() => modalCategorias.show());
  }
});
