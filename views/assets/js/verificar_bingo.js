const btnVerificar = document.querySelector("#btnVerificar");

btnVerificar.addEventListener("click", async () => {
  let arregloBalotas = JSON.parse(localStorage.getItem("navegadorBalotas"));
  console.log(arregloBalotas);
  let formData = new FormData();
  formData.append("arregloBalotas", JSON.stringify(arregloBalotas));

  const request = await fetch("../controllers/verificar_bingo.php", {
    method: "POST",
    body: formData,
  });

  const response = await request.json();

  if (response.success) {
    let tabla = `
                    <table class="table table-striped table-bordered nowrap" style="width:100%;text-align:left;">
                         <thead>
                          <tr> 
                          <th class="fw-bold fs-5"> <i class="fa-solid fa-table text-warning"></i> Tabla </th>
                          <th class="fw-bold fs-5"><i class="fa-solid fa-id-badge text-primary"></i> Aprendiz</th>
                          <th class="fw-bold fs-5"><i class="fa-solid fa-check-to-slot text-success"></i> Verificacion</th>
                        </tr>
                      </thead>
                        <tbody>
                `;

    response.tablasConteo.forEach((item) => {
      tabla += `
                        <tr class="p-5">
                        <td>${item.id} </td>
                            <td>${item.nombre_aprendiz}</td>
                            <td>${item.conteo} / 25</td>
                        </tr>
                    `;
    });

    tabla += `
                </tbody>
                </table>
                `;

    Swal.fire({
      title: `<h1 class="m-0 fw-bold">Tablas del bingo</h1>`,
      html: tabla,
      icon: "success",
      width: 700,
      confirmButtonText: "Aceptar",
      customClass: {
        confirmButton: "btn btn-success fs-5 fw-bold",
      },
    });
  }
});
