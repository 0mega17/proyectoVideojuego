// DOM
const modalAdministradores = new bootstrap.Modal("#modalAdministradores");
const btnAgregar = document.querySelector("#btnAgregar");
const tblAdministradores = document.querySelector("#tblAdministradores");
const frmAdministradores = document.querySelector("#frmAdministradores");

// VARIABLES
let modoEdicion = false;
let IDeditar = 0;
let IDreintegrar = 0;

// CAMPOS
const txtNombre = document.querySelector("#txtNombre");
const txtEmail = document.querySelector("#txtEmail");
const txtPassword = document.querySelector("#txtPassword");

// EVENTO AGREGAR
btnAgregar.addEventListener("click", async () => {
  modoEdicion = false;
  modalAdministradores.show();
  frmAdministradores.reset();
  txtPassword.setAttribute("required", "true");
});

// EVENTOS DE TABLA (EDITAR / ELIMINAR)
tblAdministradores.addEventListener("click", async (e) => {

  // ==========================
  // ELIMINAR
  // ==========================
  if (e.target.classList.contains("btnEliminar")) {
    let nombre = e.target.dataset.nombre;

    Swal.fire({
      title: `<h1 class="m-0 fw-bold">Eliminar</h1>`,
      html: `¿Esta seguro de eliminar este administrador?`,
      icon: "info",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonText: "Si, eliminar",
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger",
      },
      preConfirm: async () => {
        IDeliminar = e.target.dataset.id;

        let formData = new FormData();
        formData.append("IDeliminar", IDeliminar);

        const request = await fetch(
          "../controllers/controllerEliminarAdmin.php",
          {
            method: "POST",
            body: formData,
          }
        );

        const response = await request.json();

        if (response.success) {
          Swal.fire({
            title: `<h1 class="m-0 fw-bold">¡Exito!</h1>`,
            text: response.message,
            icon: "success",
            timer: 2000,
            showConfirmButton: false,
          }).then(() => {
            location.reload();
          });
        }
      },
    });
  }

  // ==========================
  // REINTEGRAR
  // ==========================
  if (e.target.classList.contains("btnReintegrar")) {
    let nombre = e.target.dataset.nombre;

    Swal.fire({
      title: `<h1 class="m-0 fw-bold">Reintegrar</h1>`,
      html: `¿Esta seguro de reintegrar este administrador?`,
      icon: "info",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      confirmButtonText: "Si, reintegrar",
      customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger",
      },
      preConfirm: async () => {
        IDreintegrar = e.target.dataset.id;

        let formData = new FormData();
        formData.append("IDreintegrar", IDreintegrar);

        const request = await fetch("../controllers/controllerReintegrar.php", {
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
          }).then(() => {
            location.reload();
          });
        }
      },
    });
  }
});

// ENVÍO DEL FORMULARIO (CREAR O EDITAR)
frmAdministradores.addEventListener("submit", async (e) => {
  e.preventDefault();
  modalAdministradores.hide();

  const url = modoEdicion
    ? "../controllers/controllerEditarAdmin.php"
    : "../controllers/controllerCrearAdmin.php";

  const formData = new FormData(frmAdministradores);

  if (IDeditar > 0) {
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
    }).then(() => {
      location.reload();
    });
  } else {
    Swal.fire({
      title: `<h1 class="m-0 fw-bold">¡Error!</h1>`,
      text: response.message,
      icon: "error",
      timer: 2000,
      showConfirmButton: false,
    }).then(() => {
      modalAdministradores.show();
    });
  }
});
