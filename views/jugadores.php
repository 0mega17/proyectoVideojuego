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
$pagina = "Aprendices";
//=====================================
// lAYOUT HTML
//===================================

require_once './layout/head.php';
require_once './layout/menu.php';
require_once './layout/navbar.php';

?>


<div class="row border-bottom border-2 p-2 mb-5">
    <div class="col-sm-6">
        <h2 class="fw-semibold m-0">Jugadores</h2>
        <p class="text-muted m-0">Gestiona todo lo relacionado con los jugadores del bingo</p>
    </div>
</div>


<div class="row g-3 mb-3">
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="mb-2 text-muted">
                    <i class="fa-solid fa-hashtag me-1"></i>
                    Codigo
                </h6>
                <button type="button" class="btn btn-info w-100 fw-bold" id="Btncodigo"></button>
            </div>
        </div>

    </div>

    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="mb-2 text-muted">
                    <i class="fa-solid fa-users me-1"></i>
                    Jugadores
                </h6>
                <button id="btnJugadores" class="btn btn-dark w-100 fw-bold"></button>
            </div>
        </div>

    </div>

    <div class="col-lg-4 col-md-12">
        <div class="card h-100">
            <div class="card-body text-center">
                <h6 class="mb-2 text-muted">
                    <i class="fa-solid fa-hourglass-start"></i>
                    Accion
                </h6>
                <button id="btnIniciar" class="btn btn-success w-100">
                    <i class="fa-solid fa-play me-1"></i>
                    Iniciar juego
                </button>
            </div>
        </div>

    </div>
</div>




<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header border">
                <h5 class="m-0">
                    <i class="fa-solid fa-list"></i>
                    Listado de jugadores
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped text-nowrap" id="tblGeneral">
                        <thead>
                            <th class="fw-bold">
                                <i class="fa-solid fa-circle-user"></i>
                                Nombre
                            </th>
                            <th class="fw-bold">
                                <i class="fa-solid fa-thumbtack"></i>
                                Ficha
                            </th>
                            <th class="fw-bold">
                                <i class="fa-solid fa-thumbtack"></i>
                                Acciones
                            </th>
                        </thead>
                        <tbody id="tblJugadores">

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