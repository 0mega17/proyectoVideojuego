<?php
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

$sql = "SELECT * FROM aprendices ORDER BY id DESC";
$consulta = $mysql->getConexion()->prepare($sql);
$consulta->execute();

// Generar filas dinámicas
$lista = [];
while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $lista [] = [
        'id' => $fila['id'],
        'nombre' => $fila['nombre'],
        'ficha' => $fila['ficha'],
    ];
}

echo json_encode($lista);
