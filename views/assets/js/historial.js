// aqui lo que se tiene que hacer es capturar el id de la sala al omento de que se le da click al
// boton y se le me manda al controlador el id y de esa forma booraria la sala esto se hace con el
// proposito  para que si en algun momento se crea una sala mal se pueda eliminar :3

const tblCategorias = document.querySelector("#tblCategorias");

tblCategorias.addEventListener("click", function (e) {
    if (e.target.classList.contains("btnEliminar")) {
        const id = e.target.dataset.id;
        const codigo = e.target.dataset.codigo;
        Swal.fire({
          title: `<h1 class="m-0 fw-bold">Eliminar</h1>`,
          html: `¿Está seguro de eliminar esta sala?<br>
           `,
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
            formData.append("id", id);
            formData.append("codigo", codigo);
            const request = await fetch(
              "../controllers/controlador_eliminar_sala.php",
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
              }).then(() => location.reload());
            }
          },
        });
    }
});