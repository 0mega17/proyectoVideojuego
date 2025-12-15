<?php

// Conectar a la BD
require_once '../models/MySQL.php';
$mysql = new MySQL();

$mysql->conectar();

try {
    $sql = "SELECT * FROM categorias where id != 0";
    $consultaCategorias = $mysql->getConexion()->prepare($sql);
    $consultaCategorias->execute();
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la consulta... " . $e->getMessage()
    ]);
    exit();
}


$categorias = [];

while ($fila = $consultaCategorias->fetch(PDO::FETCH_ASSOC)) {
    $categorias[] = $fila;
}
header("ContentType:application/json");
echo json_encode($categorias);
exit();
