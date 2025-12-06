<script>
  let codigoSala = localStorage.getItem("codigoSala");
  let modoJuego = localStorage.getItem("modoJuego");
  let categoria = localStorage.getItem("categoria");

  // Enviar datos a PHP mediante redirect con parámetros
  window.location.href = "bingoTablas.php?modo=" + modoJuego + "&categoria=" + categoria;
</script>
<?php
require_once "../models/MySQL.php"; // tu archivo de conexión
$mysql = new MySQL();
$mysql->conectar();
$modoJuego = $_GET["modo"] ?? "general";
$categoria = $_GET["categoria"] ?? null;


function obtenerElementoRandom($mysql, &$usados, $modoJuego, $categoria)
{
  $db = $mysql->getConexion();

  if ($modoJuego === "categoria" && $categoria !== "sin categoria") {
      // SOLO valores de la categoría seleccionada
      $sql = "SELECT c.titulo, c.autor, c.frase 
              FROM composiciones c
              JOIN categorias_has_composiciones cc ON cc.composiciones_id = c.id
              JOIN categorias ca ON ca.id = cc.categorias_id
              WHERE ca.nombre = :categoria
              ORDER BY RAND()
              LIMIT 1";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(":categoria", $categoria, PDO::PARAM_STR);
      $stmt->execute();
  } else {
      // MODO GENERAL
      $sql = "SELECT titulo, autor, frase 
              FROM composiciones 
              ORDER BY RAND() 
              LIMIT 1";
      $stmt = $db->query($sql);
  }

  $fila = $stmt->fetch(PDO::FETCH_ASSOC);
  $valores = array_values($fila);
  $valor = $valores[array_rand($valores)];

  // evitar repetidos
  if (!in_array($valor, $usados)) {
    $usados[] = $valor;
    return $valor;
  }

  // si está repetido... buscar otro
  return obtenerElementoRandom($mysql, $usados, $modoJuego, $categoria);
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
          $valor = obtenerElementoRandom($mysql, $usados, $modoJuego, $categoria);

          echo "<td>$valor</td>";
        }

        echo "</tr>";
      }
      ?>
    </tbody>
  </table>

</body>
<script src="./assets/js/pintarBingo.js"></script>
<script src="./assets/js/crear_sala.js"></script>

</html>