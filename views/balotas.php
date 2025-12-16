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


<div id="capaTitulo" class="row border-bottom border-2 p-2 mb-2">
    <div class="col-sm-9">
        <div>
            <h2 class="fw-semibold m-0">Balotas del bingo</h2>
            <p class="text-muted m-0">Genera las balotas para jugar el bingo</p>
        </div>
    </div>

    <div class="col-sm-3 d-sm-flex justify-content-center align-items-center my-2">
        <div>
            <button class="btn btn-success" id="btnVerificar">
                <i class="fa-solid fa-check-to-slot"></i>
                Verificar bingo</button>
        </div>
    </div>

</div>
<div class="row g-3 mb-3">
    <div class="col-lg-3 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">
                    <i class="fa-solid fa-hashtag me-1"></i>
                    Código
                </h6>
                <button type="button" class="btn btn-info fw-bold w-100" id="Btncodigo">-</button>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">
                    <i class="fa-solid fa-layer-group me-1"></i>
                    Categoría
                </h6>
                <button id="btnCategoria" class="btn btn-warning fw-bold w-100">-</button>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="text-muted mb-2">
                    <i class="fa-solid fa-users me-1"></i>
                    Jugadores
                </h6>
                <button id="btnJugadores" class="btn btn-dark fw-bold w-100">-</button>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card h-100">
            <div class="card-body d-flex flex-column justify-content-center gap-2">
                <button data-accion="reiniciar" id="btnReiniciar" class="btn btn-primary fw-bold">
                    <i class="fa-solid fa-rotate-left me-1"></i>
                    Reiniciar
                </button>
                <button id="btnFinalizar" class="btn btn-danger fw-bold">
                    <i class="fa-solid fa-rectangle-xmark me-1"></i>
                    Finalizar
                </button>
            </div>
        </div>
    </div>
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