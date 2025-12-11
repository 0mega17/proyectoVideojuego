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


//=====================================
// lAYOUT HTML
//===================================

require_once './layout/head.php';
require_once './layout/menu.php';
require_once './layout/navbar.php';

?>


<div class="d-flex justify-content-between align-items-center border-bottom border-2 p-2 mb-2">
    <div id="capaTitulo" class="">
        <h2 class="fw-semibold m-0 me-1">Balotas del bingo</h2>
        <p class="text-muted m-0">Genera las balotas para jugar el bingo</p>
    </div>
    <div class="mt-3">
        <button type="button" class="btn btn-info m-1" id="Btncodigo"></button>
        <button data-accion="reiniciar" id="btnReiniciar" class="btn btn-primary m-1">
            <i class="fa-solid fa-rotate-left me-1"></i>
            Reiniciar juego
        </button>

        <button id="btnFinalizar" class="btn btn-danger m-1">
            <i class="fa-solid fa-trash"></i>
            Finalizar juego
        </button>
    </div>
</div>

<div class="row p-2 mb-2">
    <div class="col-sm-12">
        <div id="ultimaBalota" class="my-2">
        </div>

        <button id="btnBalota" class="btn btn-success w-100 p-3 fw-bold">
            <i class="fa-brands fa-dribbble fs-5 fw-bold"></i>
            Nueva balota
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
                                <i class="fa-solid fa-bookmark"></i>
                                Item
                            </th>
                            <th class="fw-bold">
                                <i class="fa-brands fa-dribbble"></i>
                                Balotas del juego
                            </th>
                            <th class="fw-bold">
                                <i class="fa-solid fa-book"></i>
                                Tipo de obra
                            </th>
                        </thead>
                        <tbody id="tblBalotas"></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>



<?php
require_once './layout/footer.php';
?>