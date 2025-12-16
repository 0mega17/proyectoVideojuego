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
$pagina = "Bingo literario";

//CONSULTA DE CONTEO DE TODAS LAS COMPOSICIONES LITERARIAS
try {
    $sql = "SELECT COUNT(*) as conteo FROM composiciones";
    $conteoObras = $mysql->getConexion()->prepare($sql);
    $conteoObras->execute();
    $conteo = $conteoObras->fetch(PDO::FETCH_ASSOC)["conteo"];
    $posibilidades = $conteo * 3;
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(
        [
            'success' => false,
            "message" => "Ocurrio un error en la consulta..." . $e->getMessage()
        ]
    );
}
$cssExtra = true;


//=====================================
// lAYOUT HTML
//===================================

require_once './layout/head.php';
require_once './layout/menu.php';
require_once './layout/navbar.php';

?>

<div id="capaTitulo" class="row border-bottom border-2 p-2 mb-2">
    <h2 class="fw-semibold m-0">Balotas del bingo</h2>
    <p class="text-muted m-0">Genera las balotas para jugar el bingo</p>
</div>
<div class="mt-3">
    <button type="button" class="btn btn-info m-2 fw-bold" id="Btncodigo"> </button>
    <button id="btnCategoria" class="btn btn-warning m-2 fw-bold"></button>
    <button id="btnJugadores" class="btn btn-dark m-2 fw-bold"></button>
    <button data-accion="reiniciar" id="btnReiniciar" class="btn btn-primary m-2 fw-bold">
        <i class="fa-solid fa-rotate-left me-1"></i>
        Reiniciar juego
    </button>

    <button id="btnFinalizar" class="btn btn-danger m-2 fw-bold">
        <i class="fa-solid fa-rectangle-xmark me-1"></i>
        Finalizar juego
    </button>
</div>


<div class="row p-2 mb-2">
    <div class="col-sm-12">
        <div id="ultimaBalota" class="my-2">
        </div>

        <button id="btnBalota" class="btn btn-success w-100 p-3 fs-5 fw-bold">
            <i class="fa-solid fa-baseball mx-1"></i>
            Nueva balota

        </button>


    </div>

</div>
<div class="row mt-3 mb-3">
    <div class="col-12 d-flex justify-content-end">
        <div style="max-width: 320px; width: 100%;">
            <input
                type="text"
                id="filtroBalotas"
                class="form-control form-control-sm shadow-sm"
                placeholder="Filtrar balotas...">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">


            <div class="card-body">
                <div id="contenedorBalotas" class="balotas-container"></div>
            </div>

        </div>
    </div>
</div>
</div>



<?php
require_once './layout/footer.php';
?>