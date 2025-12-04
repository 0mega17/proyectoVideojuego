// DOM
const modalComposiciones = new bootstrap.Modal("#modalComposiciones");
const btnAgregar = document.querySelector("#btnAgregar");
const tblComposiciones = document.querySelector("#tblComposiciones");
const colCategoria = document.querySelector("#colCategoria");
const colTipo = document.querySelector("#colTipo");
const frmComposiciones = document.querySelector("#frmComposiciones");

// VARIABLES

let modoCategorias = null;
let modoEdicion = null;
let IDeditar = 0;

// CAMPOS
const txtTitulo = document.querySelector("#txtTitulo");
const txtAutor = document.querySelector("#txtAutor");
const txtFrase = document.querySelector("#txtFrase");

// EVENTO AGREGAR
btnAgregar.addEventListener("click", async () => {
  modoEdicion = false;
  modalComposiciones.show();
  frmComposiciones.reset();
});

tblComposiciones.addEventListener("click", async (e) => {
  if (e.target.classList.contains("btnEditar")) {
    IDeditar = e.target.dataset.id;
    modoEdicion = true;

    // ID para editar los datos de la fila seleccionada
    let formData = new FormData();
    formData.append("IDeditar", IDeditar);
    const request = await fetch("../controllers/datos_composicion.php", {
      method: "POST",
      body: formData,
    });

    const response = await request.json();

    // Value de los datos almcenados en la BD
    txtTitulo.value = response.composiciones.titulo;
    txtAutor.value = response.composiciones.autor;
    txtFrase.value = response.composiciones.frase;

    // SELECCIONAR LAS OPCIONES de tipo de MATERIAL
    let opcionesTipos = document.querySelectorAll(".optionTipo");

    // Limpiar las opciones seleccionadas
    opcionesTipos.forEach((opcion) => {
      opcion.selected = false;
    });

    // Seleccionar la que tiene actualmente
    opcionesTipos.forEach((opcion) => {
      if (opcion.value == response.composiciones.tipo_material_id) {
        opcion.selected = true;
      }
    });

    // SELECCIONAR LAS OPCIONES de categorias
    let opcionesCategorias = document.querySelectorAll(".optionCat");
    // limpiar las opciones seleccionadas
    opcionesCategorias.forEach((opcion) => {
      opcion.selected = false;
    });
    // Seleccionar las categorias actuales
    opcionesCategorias.forEach((opcion) => {
      response.categorias.forEach((IDcat) => {
        if (opcion.value == IDcat) {
          opcion.selected = true;
        }
      });
    });

    modalComposiciones.show();
    console.log(response);
  }
});

// CARGAR LOS DATOS DEL SELECT
document.addEventListener("DOMContentLoaded", () => {
  // Tipos de material
  cargarDatosSelect(
    "../controllers/datos_tipo.php",
    colTipo,
    false,
    "selectTipos",
    "selectTipos",
    "tipo",
    "Tipo de material",
    "optionTipo"
  );

  // Categorias
  cargarDatosSelect(
    "../controllers/datos_categoria.php",
    colCategoria,
    true,
    "selectCategorias",
    "selectCategorias",
    "categorias[]",
    "Categorias",
    "optionCat"
  );
});

async function cargarDatosSelect(
  url,
  col,
  modoCategorias,
  id,
  clase,
  name,
  lblTxt,
  claseOption
) {
  let opcionesSelect = "";
  // =============================
  // MOSTRAR TODAS LAS CATEGORIAS DE LAS COMPOSICIONES
  // =============================
  const request = await fetch(url, {
    method: "GET",
  });

  const response = await request.json();

  let atributoMultiple = modoCategorias ? 'multiple = "multiple"' : "";
  let aux = 0;
  response.forEach((dato) => {
    opcionesSelect += `<option class="${claseOption}" value="${dato.id}"> ${dato.nombre}</option>`;
  });

  const htmlSelect = `
  <label class="form-label"> ${lblTxt} </label>
  <select id="${id}" class="form-control ${clase}" name="${name}" required 
  ${atributoMultiple}> 
   ${opcionesSelect}
  </select>
  `;
  col.innerHTML = htmlSelect;
}
// Se ejecuta despues de abrir la modal para inicializar select 2
modalComposiciones._element.addEventListener("shown.bs.modal", () => {
  $(".selectCategorias").select2({
    theme: "bootstrap-5",
    width: $(this).data("width")
      ? $(this).data("width")
      : $(this).hasClass("w-100")
      ? "100%"
      : "style",
    dropdownParent: $("#modalComposiciones"),
    language: {
      noResults: function () {
        return "No hay resultados";
      },
    },
  });
});

// ENVIO DE LA INFORMACION EN CREAR O EDITAR
frmComposiciones.addEventListener("submit", async (e) => {
  e.preventDefault();
  modalComposiciones.hide();

  const url = modoEdicion
    ? "../controllers/editar_composicion.php"
    : "../controllers/crear_composicion.php";
  const formData = new FormData(frmComposiciones);

  if (IDeditar > 0) {
    formData.append("IDeditar", IDeditar);
  }

  const request = await fetch(url, {
    method: "POST",
    body: formData,
  });

  response = await request.json();

  if (response.success) {
    Swal.fire({
      title: "¡Exito!",
      text: response.message,
      icon: "success",
      timer: 2000,
      showConfirmButton: false,
    }).then(() => {
      location.reload();
    });
  } else {
    Swal.fire({
      title: "¡Error!",
      text: response.message,
      icon: "error",
      timer: 2000,
      showConfirmButton: false,
    }).then(() => {
      modalComposiciones.show();
    });
  }
});
