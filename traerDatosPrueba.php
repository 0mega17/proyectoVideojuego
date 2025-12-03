<?php
header('Content-Type: application/json');
require_once './models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();
try {
$sql="SELECT composiciones.titulo,composiciones.autor, composiciones.frase FROM composiciones ORDER BY RAND()";
$datos = $mysql->getConexion()->prepare($sql);
$datos->execute();
$resultado = $datos->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($resultado);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los datos: ' . $e->getMessage()
    ]);

}


?>