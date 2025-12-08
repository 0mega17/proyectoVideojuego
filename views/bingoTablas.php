<?php

// VERIFICAR SI HAY UNA SESION INICIADA
session_start();
if (!isset($_SESSION["accesoAprendiz"]) || $_SESSION["accesoAprendiz"] !== true) {
  header('location: login_usuarios.php');
  exit();
}

//Obtener el codigo de la sala ingresado
$codigoSala = $_SESSION["codigoSala"];


require_once "../models/MySQL.php"; //  Archivo de conexión
$mysql = new MySQL();
$mysql->conectar();



// Seleccionar la categoria escogida
try {
  $sql = "SELECT categoria_codigo FROM codigos WHERE codigo = $codigoSala";
  $consultaCategoria = $mysql->getConexion()->prepare($sql);
  $consultaCategoria->execute();
  $categoria = $consultaCategoria->fetch(PDO::FETCH_ASSOC)["categoria_codigo"];
} catch (PDOException $e) {
  $error = $e->getMessage();
}



function obtenerElementoRandom($mysql, &$usados, $categoria)
{
  $db = $mysql->getConexion();

  while (true) {

    if ($categoria != null) {
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
</head>

<body class="container-fluid py-4 justify-content-center" style="background-color: #ffffffff;">

  <h1 class="text-center mb-5 text-dark">Bingo Literario</h1>
  <p class="text-center text-muted"><?php echo $codigoSala ?></p>
  <input type="hidden" id="txtCodigoSala" value="<?php echo $codigoSala ?>">

  <table class="table table-bordered table-light border border-dark border-2 text-center mt-4">
    <thead>
      <tr>
        <th style="font-size: 50px;">B</th>
        <th style="font-size: 50px;">I</th>
        <th style="font-size: 50px;">N</th>
        <th style="font-size: 50px;">G</th>
        <th style="font-size: 50px;">O</th>
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
<script src="./assets/js/reiniciar.js"></script>
<!-- Sweet alert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</html>