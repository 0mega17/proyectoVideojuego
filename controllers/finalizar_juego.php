<?php

// Conexion a la base de datos
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$errores = [];

// Codigo de la sala
$codigoSala = $_POST["codigoSala"];
$accion = "finalizar";
$estado = 0;

// Update en la accion de la sala
try {
    $sqlUpdate = "UPDATE codigos SET accion = :accion, estado = :estado WHERE codigo = :codigoSala ";
    $updateEstado = $mysql->getConexion()->prepare($sqlUpdate);
    $updateEstado->bindParam("codigoSala", $codigoSala, PDO::PARAM_INT);
    $updateEstado->bindParam("accion", $accion, PDO::PARAM_STR);
    $updateEstado->bindParam("estado", $estado, PDO::PARAM_INT);
    $updateEstado->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en el update de la sala..." . $e->getMessage();
}

try {
    $sqlTruncate = "TRUNCATE aprendices";
    $consultaTruncate = $mysql->getConexion()->prepare($sqlTruncate);
    $consultaTruncate->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en el truncate..." . $e->getMessage();
}



if (count($errores) == 0) {
    echo json_encode([
        "success" => true,
        "message" => "El juego fue finalizado exitosamente"
    ]);
}
