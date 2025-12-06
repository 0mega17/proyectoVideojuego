<?php

// Conexion a la base de datos
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$errores = [];

// Lee el stream de entrada sin procesar
$json_string = file_get_contents('php://input');
// Captura del objeto con los datos de la sala
$codigoSala = json_decode($json_string, true);
$estado = intval(2);

try {
    $numAleatorio = mt_rand(0,9);
    $sqlUpdate = "UPDATE codigos SET estado = :estado WHERE codigo = :codigoSala ";
    $updateEstado = $mysql->getConexion()->prepare($sqlUpdate);
    $updateEstado->bindParam("codigoSala", $codigoSala, PDO::PARAM_INT);
    $updateEstado->bindParam("estado", $numAleatorio, PDO::PARAM_INT);
    $updateEstado->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en el update de la sala..." . $e->getMessage();
}



if (count($errores) == 0) {
    echo json_encode([
        "success" => true,
        "message" => "El juego fue reiniciado exitosamente"
    ]);
}
