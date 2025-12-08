<?php

// Conexion a la base de datos
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

$codigo = $_POST["codigo"];

try {

    $sql = "SELECT updated_at, accion FROM codigos WHERE codigo = :codigo";
    $consultaEstado = $mysql->getConexion()->prepare($sql);
    $consultaEstado->bindParam("codigo", $codigo);
    $consultaEstado->execute();
    $estado = $consultaEstado->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Ocurrio un error en la consulta" . $e->getMessage()
    ]);
    exit();
}


header("ContentType:application/json");
echo json_encode([
    "success" => true,
    "estado" => $estado,
]);
