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
$pagina = "Salas";

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
        <h2 class="fw-semibold m-0">Salas de juego</h2>
        <p class="text-muted m-0">Crea las salas de juego para el Bingo literario</p>
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
                    Espacio para crear nuevas salas
                </h5>
            </div>

            <div class="card-body">

                <!--  aqui  se debe de hacer la generacion de las salas  primero  -->
                <form action="" id="formularioSala">
                    <div class="row">

                        <div class="col-sm-3">
                            <div class="mb-6">
                                <label for="ficha" class="form-label">Cantidad jugadores</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="cantidadJugadores"
                                    name="cantidadJugadores"
                                    placeholder="Ingrese la cantidad de jugadores"
                                    autofocus />
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-6">
                                <label for="modoJuego" class="form-label">Modo de juego</label>
                                <select class="form-control" id="modoJuego" name="modoJuego">
                                    <option value="general">General</option>
                                    <option value="categoria">Categorias</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="mb-6">
                                <label for="categoria" class="form-label">Categorias</label>
                                <select class="form-control selectCategorias" id="categoria" name="categoria" disabled hidden>

                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn rounded-pill btn-success mt-5 w-100">Crear sala</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php
require_once './layout/footer.php';
?>