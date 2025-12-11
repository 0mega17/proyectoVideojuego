<?php
// VERIFICAR SI HAY UNA SESION INICIADA
session_start();
if (!isset($_SESSION["acceso"]) || $_SESSION["acceso"] !== true) {
    header('location: login_admin.php');
    exit();
}
$pagina = "Aprendices";
//=====================================
// lAYOUT HTML
//===================================

require_once './layout/head.php';
require_once './layout/menu.php';
require_once './layout/navbar.php';

?>


<div class="d-flex justify-content-between border-bottom border-2 p-2 mb-5">
    <div class="">
        <h2 class="fw-semibold m-0">Jugadores</h2>
        <p class="text-muted m-0">Gestiona todo lo relacionado con los jugadores del bingo</p>
    </div>
    <div class="mt-3">
        <button type="button" class="btn btn-info" id="Btncodigo"></button>
        <button id="btnIniciar" class="btn btn-primary">
            <i class="fa-solid fa-play"></i>
            Iniciar juego
        </button>
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