<?php


// MODELO DE LA BD
require_once '../../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

//CONSULTA DE TODAS LAS COMPOSICIONES LITERARIAS
try {
    $sql = "SELECT composiciones.id, composiciones.titulo, composiciones.autor, composiciones.frase, tipo_material.tipo FROM composiciones JOIN tipo_material ON tipo_material.id  = composiciones.tipo_material_id";

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


<div class="d-flex justify-content-between border-bottom p-2">
    <div class="">
        <h2 class="fw-semibold m-0">Material bibliografico</h2>
        <p class="text-muted m-0">Gestiona todo lo relacionado con los elementos para el bingo literario</p>
    </div>
    <div class="mt-3">
        <button class="btn btn-primary">Agregar nuevo material</button>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0">Listado de material bibliografico</h6>
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
                            <th class="fw-bold">Tipo</th>
                            <th class="fw-bold">Categorias</th>
                            <th class="fw-bold">Acciones</th>
                        </thead>
                        <tbody>
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
                                        <?php echo $fila["frase"] ?>
                                    </td>
                                    <td>
                                        <?php echo $fila["tipo"] ?>
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
                                            <button class="btn btn-primary btn-sm">
                                                <i class='bx bx-edit'></i>
                                                Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm">
                                                <i class='bx  bx-trash'></i>
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