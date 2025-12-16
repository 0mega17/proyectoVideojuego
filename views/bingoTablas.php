<?php

// VERIFICAR SI HAY UNA SESION INICIADA
session_start();
if (!isset($_SESSION["accesoAprendiz"]) || $_SESSION["accesoAprendiz"] !== true) {
  header('location: login_usuarios.php');
  exit();
}

//Obtener el codigo de la sala ingresado
$codigoSala = $_SESSION["codigoSala"];
$idAprendiz = $_SESSION["idAprendiz"];
$nombreAprendiz = $_SESSION["nombreAprendiz"];
$fichaAprendiz = $_SESSION["fichaAprendiz"];


require_once "../models/MySQL.php"; //  Archivo de conexión
$mysql = new MySQL();
$mysql->conectar();



// Seleccionar la categoria escogida
try {
  $sql = "SELECT categoria_codigo FROM codigos WHERE codigo = :codigoSala";
  $consultaCategoria = $mysql->getConexion()->prepare($sql);
  $consultaCategoria->bindParam("codigoSala", $codigoSala);
  $consultaCategoria->execute();
  $categoria = $consultaCategoria->fetch(PDO::FETCH_ASSOC)["categoria_codigo"];
} catch (PDOException $e) {
  $error = $e->getMessage();
}

try {
  if ($categoria != 0) {
    $sql = "SELECT nombre FROM categorias WHERE id = :categoria";
    $consultaNombreCat = $mysql->getConexion()->prepare($sql);
    $consultaNombreCat->bindParam("categoria", $categoria);
    $consultaNombreCat->execute();
    $nombreCat = $consultaNombreCat->fetch(PDO::FETCH_ASSOC)["nombre"];
  } else {
    $nombreCat = "General";
  }
} catch (PDOException $e) {
  $error = $e->getMessage();
}

// Obtener el numero de la tabla generada
try {
  $sql = "SELECT id FROM tablas WHERE aprendices_id = :aprendizID";
  $consultaTablaID = $mysql->getConexion()->prepare($sql);
  $consultaTablaID->bindParam("aprendizID", $idAprendiz);
  $consultaTablaID->execute();
  $tablaID = $consultaTablaID->fetch(PDO::FETCH_ASSOC)["id"];
} catch (PDOException $e) {
  $error = $e->getMessage();
}

function obtenerElementoRandom($mysql, &$usados, $categoria)
{
  $db = $mysql->getConexion();

  while (true) {

    if ($categoria != "0") {
      //! Tomar fila aleatoria
      $sql = "SELECT titulo, autor, frase 
                FROM composiciones
                JOIN categorias_has_composiciones ON
                categorias_has_composiciones.composiciones_id = composiciones.id
                WHERE categorias_has_composiciones.categorias_id = $categoria 
                ORDER BY RAND() 
                LIMIT 1";
    } else {
      //! Tomar fila aleatoria
      $sql = "SELECT titulo, autor, frase 
                FROM composiciones 
                ORDER BY RAND() 
                LIMIT 1";
    }


    $stmt = $db->query($sql);
    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    //! Elegir uno de los campos
    $valores = array_values($fila);
    $valor = $valores[array_rand($valores)];

    //! Si NO está repetido, lo devolvemos
    if (!in_array($valor, $usados)) {
      $usados[] = $valor; //! Guardar como usado
      return $valor;
    }

    //! Si está repetido → vuelve a intentar
  }
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title class="text-dark">Bingo Literario</title>
  <link rel="stylesheet" href="./assets/vendor/css/core.css">
  <link rel="stylesheet" href="./assets/css/tablasBingo.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Favicon -->
  <link
    rel="icon"
    type="image/x-icon"
    href="./assets/img/logoSena.png" />
</head>

<body class="justify-content-center">

  <div class="bg-success bg-opacity-10 border-bottom border-success border-3">
    <div class="container-fluid py-4">
      <!-- Título principal -->
      <div class="text-center mb-4">
        <h1 class="fw-bold text-success mb-2">
          Bingo Literario
        </h1>
        <p class="text-success mb-0 fw-semibold">¡Completa tu tabla y gana el bingo!</p>
      </div>

      <!-- Información del juego -->
      <div class="row g-3 justify-content-center">
        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card text-center bg-info bg-opacity-10 border-info border-2 h-100">
            <div class="card-body py-3">
              <div class="text-info mb-2">
                <i class="fas fa-hashtag fs-3"></i>
              </div>
              <small class="text-secondary d-block fw-semibold">Código Sala</small>
              <strong class="text-dark fs-4"><?php echo $codigoSala ?></strong>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card text-center bg-success bg-opacity-10 border-success border-2 h-100">
            <div class="card-body py-3">
              <div class="text-success mb-2">
                <i class="fas fa-layer-group fs-3"></i>
              </div>
              <small class="text-secondary d-block fw-semibold">Categoría</small>
              <strong class="text-dark fs-4"><?php echo $nombreCat ?></strong>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card text-center bg-warning bg-opacity-10 border-warning border-2 h-100">
            <div class="card-body py-3">
              <div class="text-warning mb-2">
                <i class="fas fa-user fs-3"></i>
              </div>
              <small class="text-secondary d-block fw-semibold">Jugador</small>
              <strong class="text-dark fs-4"><?php echo $nombreAprendiz ?></strong>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card text-center bg-primary bg-opacity-10 border-primary border-2 h-100">
            <div class="card-body py-3">
              <div class="text-primary mb-2">
                <i class="fas fa-id-card fs-3"></i>
              </div>
              <small class="text-secondary d-block fw-semibold">Ficha</small>
              <strong class="text-dark fs-4"><?php echo $fichaAprendiz ?></strong>
            </div>
          </div>
        </div>

        <div class="col-lg-2 col-md-4 col-sm-6">
          <div class="card text-center bg-danger bg-opacity-10 border-danger border-2 h-100">
            <div class="card-body py-3">
              <div class="text-danger mb-2">
                <i class="fas fa-table-cells fs-3"></i>
              </div>
              <small class="text-secondary d-block fw-semibold">Tabla N°</small>
              <strong class="text-dark fs-4"><?php echo $tablaID ?></strong>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <input type="hidden" id="txtCodigoSala" value="<?php echo $codigoSala ?>">
  

  <table class="tabla-bingo">
    <thead>
      <tr>
        <th>B</th>
        <th>I</th>
        <th>N</th>
        <th>G</th>
        <th>O</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $usados = []; //! Para evitar repeticiones
      for ($r = 0; $r < 5; $r++) {

        echo "<tr>";

        for ($c = 0; $c < 5; $c++) {

          //! Obtener un valor aleatorio desde la base de datos
          $valor = obtenerElementoRandom($mysql, $usados, $categoria);

          echo "<td>$valor</td>";
        }

        echo "</tr>";
      }
      ?>
    </tbody>
  </table>

</body>
<script src="./assets/js/pintarBingo.js"></script>
<script src="./assets/js/reiniciar_finalizar.js"></script>
<script src="./assets/js/validar_sesion.js"></script>
<!-- Sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>