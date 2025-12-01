<?php


// MODELO DE LA BD
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();
$pagina = "Composiciones";

//CONSULTA DE TODAS LAS COMPOSICIONES LITERARIAS
try {
    $sql = "SELECT composiciones.id, composiciones.titulo, composiciones.autor, composiciones.frase, tipo_material.nombre FROM composiciones JOIN tipo_material ON tipo_material.id  = composiciones.tipo_material_id";

    $composiciones = $mysql->getConexion()->prepare($sql);
    $composiciones->execute();
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(
        [
            'success' => false,
            "message" => "Ocurrio un error en la consulta..." . $e->getMessage()
        ]
    );
}


//=====================================
// lAYOUT HTML
//===================================

require_once './layout/head.php';
require_once './layout/menu.php';
require_once './layout/navbar.php';

?>


<div class="d-flex justify-content-between border-bottom border-2 p-2 mb-5">
    <div class="">
        <h2 class="fw-semibold m-0">Composiciones literarias</h2>
        <p class="text-muted m-0">Gestiona todo lo relacionado con los elementos para el bingo literario</p>
    </div>
    <div class="mt-3">
        <button id="btnAgregar" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i>
            Agregar
        </button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header border">
                <h5 class="m-0">
                    <i class="fa-solid fa-list"></i>
                    Listado de material bibliografico
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped text-nowrap" id="tblGeneral">
                        <thead>
                            <th class="fw-bold">
                                <i class="fa-solid fa-book"></i>
                                Titulo
                            </th>
                            <th class="fw-bold">
                                <i class="fa-solid fa-circle-user"></i>
                                Autor
                            </th>
                            <th class="fw-bold">
                                <i class="fa-solid fa-envelope-open-text"></i>
                                Frase
                            </th>
                            <th class="fw-bold">
                                <i class="fa-solid fa-bookmark"></i>
                                Tipo
                            </th>
                            <th class="fw-bold">
                                <i class="fa-solid fa-layer-group"></i>
                                Categorias
                            </th>
                            <th class="fw-bold">
                                <i class="fa-solid fa-thumbtack"></i>
                                Acciones
                            </th>
                        </thead>
                        <tbody id="tblComposiciones">
                            <?php while ($fila = $composiciones->fetch(PDO::FETCH_ASSOC)): ?>

                                <?php
                                $IDcomposicion = $fila["id"];
                                try {
                                    $sqlCategorias = "SELECT categorias.nombre FROM categorias JOIN categorias_has_composiciones ON categorias_has_composiciones.categorias_id = categorias.id WHERE categorias_has_composiciones.composiciones_id = :IDcomposicion";

                                    $sqlCategorias = $mysql->getConexion()->prepare($sqlCategorias);
                                    $sqlCategorias->bindParam("IDcomposicion", $IDcomposicion, PDO::PARAM_INT);
                                    $sqlCategorias->execute();

                                    $categorias = [];

                                    while ($filaCategorias = $sqlCategorias->fetch(PDO::FETCH_ASSOC)) {
                                        $categorias[] = $filaCategorias["nombre"];
                                    }
                                } catch (PDOException $e) {
                                    http_response_code(500);
                                    echo "Error en las categorias" . $e->getMessage();
                                }

                                ?>
                                <tr>
                                    <td>
                                        <?php echo $fila["titulo"] ?>
                                    </td>
                                    <td>
                                        <?php echo $fila["autor"] ?>
                                    </td>
                                    <td>
                                        <?php echo ($fila["frase"] = null ? "N/A" : $fila["frase"]) ?>
                                    </td>
                                    <td>
                                        <?php echo $fila["nombre"] ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($categorias as $categoria) { ?>
                                            <span class="badge text-bg-info px-3 m-1 shadow rounded-pill"><?php echo $categoria ?></span>
                                        <?php }
                                        ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                                            <button class="btn btn-primary btn-sm btnEditar" data-id="<?php echo $fila["id"] ?>">
                                                <i class="fa-solid fa-pen-to-square me-1"></i>
                                                Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm" data-id="<?php echo $fila["id"] ?>">
                                                <i class="fa-solid fa-trash"></i>
                                                Eliminar
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                            <?php endwhile; ?>

                        </tbody>
                    </table>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="modalComposiciones" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">

                        <div class="modal-content">
                            <form action="" id="frmComposiciones">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalCenterTitle">Gestion de composiciones</h5>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-6">
                                            <label for="txtTitulo" class="form-label">Titulo</label>
                                            <input
                                                type="text"
                                                id="txtTitulo"
                                                name="titulo"
                                                class="form-control"
                                                placeholder="Ingrese el titulo de la obra literia"
                                                required />
                                        </div>
                                    </div>
                                    <div class="row g-6">
                                        <div class="col mb-0">
                                            <label for="txtAutor" class="form-label">Autor</label>
                                            <input
                                                type="text"
                                                id="txtAutor"
                                                name="autor"
                                                class="form-control"
                                                placeholder="Ingrese el autor de la obra literaria"
                                                required />
                                        </div>
                                    </div>

                                    <div class="row g-6 mt-1">
                                        <div class="col mb-0">
                                            <label for="txtFrase" class="form-label">Frase</label>
                                            <textarea class="form-control" rows="3" name="frase" id="txtFrase" placeholder="Ingrese una frase relacionada con la obra..."></textarea>

                                        </div>
                                    </div>

                                    <div class="row g-6 mt-1">
                                        <div id="colTipo" class="col mb-0">

                                        </div>
                                    </div>
                                    <div class="row g-6 mt-1">
                                        <div id="colCategoria" class="col mb-0">

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
require_once './layout/footer.php';
?>