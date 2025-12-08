<?php

// Conexion a la base de datos
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$errores = [];

// Codigo de la sala
$codigoSala = $_POST["codigoSala"];
$accion = "finalizar";

// Update en la accion de la sala
try {
    $sqlUpdate = "UPDATE codigos SET accion = :accion WHERE codigo = :codigoSala ";
    $updateEstado = $mysql->getConexion()->prepare($sqlUpdate);
    $updateEstado->bindParam("codigoSala", $codigoSala, PDO::PARAM_INT);
    $updateEstado->bindParam("accion", $accion, PDO::PARAM_STR);
    $updateEstado->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en el update de la sala..." . $e->getMessage();
}



if (count($errores) == 0) {
    echo json_encode([
        "success" => true,
        "message" => "El juego fue finalizado exitosamente"
    ]);
}
