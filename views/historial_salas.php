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
$pagina = "Historial Salas";

//CONSULTA DE TODOS LOS REGISTROS DE SALAS ACTIVAS
try {
    $sql = "SELECT codigos.id as id_codigo, codigos.codigo as codigo, categorias.nombre as nombre_categoria FROM codigos  inner join categorias on codigos.categoria_codigo = categorias.id where codigos.estado = 1 OR codigos.estado = 2";

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
        <h2 class="fw-semibold m-0">Historial de salas</h2>
        <p class="text-muted m-0">Gestiona todo lo relacionado con las salas</p>
    </div>
    <div class="mt-3">

    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header border">
                <h5 class="m-0">
                    <i class="fa-solid fa-list"></i>
                    Listado de salas activas
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped text-nowrap" id="tblGeneral">
                        <thead>
                            <th class="fw-bold">
                                <i class="fa-solid fa-circle-user"></i>
                                Codigo
                            </th>
                            <th class="fw-bold">
                                <i class="fa-solid fa-thumbtack"></i>
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
                                        <?php echo $fila["codigo"] ?>
                                        
                                    </td>
                                    <td>
                                        <?php echo $fila["nombre_categoria"] ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic outlined example">
                                            
                                            <button class="btn btn-danger btn-sm btnEliminar" data-id="<?php echo $fila["id_codigo"] ?>" data-codigo="<?php echo $fila["codigo"] ?>" >
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
        
                
            </div>
        </div>
    </div>
</div>



<?php
require_once './layout/footer.php';
?>