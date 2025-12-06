<?php
require_once "../models/MySQL.php";
$mysql = new MySQL();
$mysql->conectar();

// Si ya hay POST, usamos esos valores y NO auto-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $modoJuego = $_POST['modo'] ?? 'general';
  $categoria = $_POST['categoria'] ?? 'general';
  $autoSubmit = false;
} else {
  // aún no hay POST -> vamos a pedir al cliente que nos envíe modo/categoria desde localStorage
  $modoJuego = null;
  $categoria = null;
  $autoSubmit = true;
}
function obtenerValores($mysql, $modoJuego, $categoria)
{
  $db = $mysql->getConexion();

  if ($modoJuego === "categoria" && $categoria !== "general") {

    $sql = "SELECT c.titulo, c.autor, c.frase
                FROM composiciones c
                JOIN categorias_has_composiciones cc ON cc.composiciones_id = c.id
                JOIN categorias ca ON ca.id = cc.categorias_id
                WHERE ca.nombre = :categoria";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(":categoria", $categoria);
    $stmt->execute();
  } else {

    $sql = "SELECT titulo, autor, frase FROM composiciones";
    $stmt = $db->query($sql);
  }

  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $valores = [];

  foreach ($rows as $fila) {
    $valores[] = $fila["titulo"];
    $valores[] = $fila["autor"];
    $valores[] = $fila["frase"];
  }

  // Barajar
  shuffle($valores);

  return $valores;
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
<form id="formOculto" method="POST" style="display:none;">
  <input type="hidden" name="modo" id="modo">
  <input type="hidden" name="categoria" id="categoria">
</form>

<script>
  (function() {
    const autoSubmit = <?= $autoSubmit ? 'true' : 'false' ?>;
    if (!autoSubmit) return; // ya vinimos por POST, no volver a enviar

    // evitar doble envío por recargas: marcar en sessionStorage
    if (sessionStorage.getItem('bingoPosted') === '1') {
      // ya se envió una vez en esta sesión del navegador -> no reenviar
      return;
    }

    const modo = localStorage.getItem('modoJuego') || 'general';
    const categoria = localStorage.getItem('categoria') || 'general';

    document.getElementById('modo').value = modo;
    document.getElementById('categoria').value = categoria;

    // marcar como enviado para que en una recarga no vuelva a enviar
    sessionStorage.setItem('bingoPosted', '1');

    // enviar el formulario
    document.getElementById('formOculto').submit();
  })();
</script>



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
      $valores = obtenerValores($mysql, $modoJuego, $categoria);

      // aseguramos que haya al menos 25 valores
      while (count($valores) < 25) {
        $valores[] = "SIN DATOS";
      }

      $index = 0;

      for ($r = 0; $r < 5; $r++) {
        echo "<tr>";

        for ($c = 0; $c < 5; $c++) {
          echo "<td>" . $valores[$index++] . "</td>";
        }

        echo "</tr>";
      }

      ?>
    </tbody>
  </table>

</body>
<script src="./assets/js/pintarBingo.js"></script>

</html>