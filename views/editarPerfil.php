<?php
// ======================================
// 1) VERIFICAR SESIÓN
// ======================================
session_start();

if (!isset($_SESSION["acceso"]) || $_SESSION["acceso"] !== true) {
    header('location: login_admin.php');
    exit();
}

$IdUsuario = $_SESSION["IdUsuario"];
$pagina = "Perfil";

// ======================================
// 2) CONEXIÓN A LA BASE DE DATOS
// ======================================
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

// ======================================
// 3) CONSULTAR DATOS DEL USUARIO LOGEADO
// ======================================
try {

    $sql = "SELECT nombre, email FROM administradores WHERE id = :id";

    $administradores = $mysql->getConexion()->prepare($sql);
    $administradores->bindParam(':id', $IdUsuario, PDO::PARAM_INT);

    $administradores->execute();
    $usuario = $administradores->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Error: Usuario no encontrado.");
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => "Error al consultar datos: " . $e->getMessage()
    ]);
    exit();
}

// ======================================
// 4) INCLUIR LAYOUTS
// ======================================
require_once './layout/head.php';
require_once './layout/menu.php';
require_once './layout/navbar.php';

?>

<main class="app-main">
    <div class="container-fluid mt-sm-5">

        <div class="row mt-5">

            <!-- FOTO DEL PERFIL -->
            <div class="col-md-4 mb-4 d-flex justify-content-center">
                <div class="card mt-3 text-center" style="width: 18rem;">
                    <div class="card-body d-flex flex-column align-items-center">
                        <img src="./assets/img/logoSena.png"
                             class="rounded-circle shadow mb-3"
                             alt="User Image"
                             style="width: 150px; height: 150px; object-fit: cover;">

                        <h3 class="card-title mb-0">
                            <?php echo htmlspecialchars($usuario["nombre"]); ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- DATOS DEL PERFIL -->
            <div class="col-md-8 align-self-center">
                <div class="profile-card">

                    <h3 class="fw-bold-card">Detalles Perfil</h3>

                    <form method="post">

                        <!-- Información Personal -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control"
                                    id="nombre" name="nombre"
                                    value="<?php echo htmlspecialchars($usuario['nombre']); ?>">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control"
                                    id="email" name="email"
                                    value="<?php echo htmlspecialchars($usuario['email']); ?>">
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Cambio de contraseña -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Contraseña actual</label>
                                <input type="password" class="form-control"
                                    id="oldPassword" name="oldPassword"
                                    placeholder="Ingrese su contraseña actual">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control"
                                    id="newPassword" name="newPassword"
                                    disabled placeholder="Ingresa una nueva contraseña">

                                <div class="form-check mb-2 mt-3">
                                    <input class="form-check-input" type="checkbox" id="cambiarPassword">
                                    <label class="form-check-label" for="cambiarPassword">
                                        ¿Deseas cambiar tu contraseña?
                                    </label>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <div class="d-flex justify-content-end m-4">
        <button type="button" class="btn btn-success me-2 fw-bold" id="btnGuardar">
            <i class="fa-solid fa-circle-check me-2"></i>
            Guardar
        </button>
        <a href="./sala.php" class="btn btn-primary fw-bold">
            <i class="fa-solid fa-circle-left"></i>
            Volver a Inicio
        </a>
    </div>

</main>
<?php
require_once './layout/footer.php';
?>