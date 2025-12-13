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

// Conteo de totales
try {
    $sql = "SELECT COUNT(*) as conteoGeneral FROM codigos";
    $consultaConteo = $mysql->getConexion()->prepare($sql);
    $consultaConteo->execute();
    $conteoGeneral = $consultaConteo->fetch(PDO::FETCH_ASSOC)["conteoGeneral"];
} catch (PDOException $e) {
    $error = $e->getMessage();
}

// Conteo de partidas jugadas por categoria o general
try {
    $sql = "SELECT COUNT(*) as conteo, categoria_codigo, COALESCE((SELECT categorias.nombre FROM categorias WHERE categorias.id = categoria_codigo ), 'General') as nombre_categoria FROM codigos GROUP BY categoria_codigo ORDER BY conteo DESC LIMIT 5;";
    $consultaConteo = $mysql->getConexion()->prepare($sql);
    $consultaConteo->execute();

    $arregloBadge = ["text-bg-success", "text-bg-primary", "text-bg-rosado", "text-bg-danger", "text-bg-warning"];

    $arregloImg = [
        "../views/assets/img/libro-verde.png",
        "../views/assets/img/libro-azul.png",
        "../views/assets/img/libro-rosado.png",
        "../views/assets/img/libro-rojo.png",
        "../views/assets/img/libro-amarillo.png",
    ];
    $contadorBadge = 0;
} catch (PDOException $e) {
    $error = $e->getMessage();
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
    <div class="col-12 mb-3">
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
                                    min="1"
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
                                <label for="categoria" class="form-label" id="labelCategoria" hidden>Categorias</label>
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

<div class="row mt-5">
    <div class="col-5 col-sm-2 mb-4 mx-auto">
        <div class="card shadow-lg">
            <div class="card-body">
                <div class="card-title d-flex align-items-start mb-0 justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img
                            src="../views/assets/img/libro-total.png"
                            alt="chart success"
                            class="rounded" />
                    </div>
                    <h3 class="card-title mb-2"><?php echo $conteoGeneral ?></h3>
                </div>
                <small class="text-dark fw-semibold">Total de partidas</small>
                <span class="fw-semibold d-block mb-1 badge text-bg-info ?>">Total</span>


            </div>
        </div>
    </div>
    <?php while ($fila = $consultaConteo->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col-5 col-sm-2 mb-4 mx-auto">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start mb-0 justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img
                                src="<?php echo $arregloImg[$contadorBadge] ?>"
                                alt="chart success"
                                class="rounded" />
                        </div>
                        <h3 class="card-title mb-2"><?php echo $fila["conteo"] ?></h3>
                    </div>
                    <small class="text-dark fw-semibold">Total de partidas</small>
                    <span class="fw-semibold d-block mb-1 badge <?php echo $arregloBadge[$contadorBadge] ?>"><?php echo $fila["nombre_categoria"] ?></span>


                </div>
            </div>
        </div>

    <?php
        $contadorBadge++;
    endwhile ?>


</div>


<?php
require_once './layout/footer.php';
?>