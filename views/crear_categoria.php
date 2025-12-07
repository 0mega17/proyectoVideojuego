<?php
// VERIFICAR SI HAY UNA SESION INICIADA
session_start();
if (!isset($_SESSION["acceso"]) || $_SESSION["acceso"] !== true) {
    header('location: login_admin.php');
    exit();
}

// MODELO DE LA BD
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();
$pagina = "Categorias";

//CONSULTA DE TODAS LAS CATEGORIAS
try {
    $sql = "SELECT * FROM categorias";

    $categorias = $mysql->getConexion()->prepare($sql);
    $categorias->execute();
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
        <h2 class="fw-semibold m-0">Categoria obras</h2>
        <p class="text-muted m-0">Gestiona todo lo relacionado con las categorias de las obras</p>
    </div>
    <div class="mt-3">
        <button id="btnAgregar" class="btn btn-success">
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
                    Listado de categorias
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped text-nowrap" id="tblGeneral">
                        <thead>
                            <th class="fw-bold">
                                <i class="fa-solid fa-circle-user"></i>
                                Categoria
                            </th>
                            <th class="fw-bold">
                                <i class="fa-solid fa-thumbtack"></i>
                                Acciones
                            </th>
                        </thead>
                        <tbody id="tblCategorias">
                            <?php while ($fila = $categorias->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td>
                                        <?php echo $fila["nombre"] ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                                            <button class="btn btn-primary btn-sm btnEditar" data-id="<?php echo $fila["id"] ?>">
                                                <i class="fa-solid fa-pen-to-square me-1"></i>
                                                Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm btnEliminar" data-id="<?php echo $fila["id"] ?>" data-nombre="<?php echo $fila["nombre"] ?>">
                                                <i class=" fa-solid fa-trash"></i>
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
                <div class="modal fade" id="modalCategorias" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered rounded" role="document">
                        <div class="modal-content">
                            <form action="" id="frmCategorias">
                                <div class="modal-header bg-success-subtle p-5">
                                    <h5 class="modal-title text-success m-0" id="modalCenterTitle">Gestion de categorias</h5>
                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col mb-6">
                                            <label for="txtCategoria" class="form-label">Categoria</label>
                                            <input
                                                type="text"
                                                id="txtCategoria"
                                                name="categoria"
                                                class="form-control"
                                                placeholder="Ingrese el nombre de la categoria"
                                                required />
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-success">Guardar</button>
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