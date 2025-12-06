<?php

// Conexion a la base de datos
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$errores = [];

// Codigo de la sala
$codigoSala = $_POST["codigoSala"];
$estado = 0;

// Seleccionar estado actual
try{
    $sqlEstado = "SELECT estado FROM codigos WHERE codigo = :codigoSala";
    $consultaEstado = $mysql->getConexion()->prepare($sqlEstado);
    $consultaEstado->bindParam("codigoSala", $codigoSala, PDO::PARAM_INT);
    $consultaEstado->execute();

    $estado = $consultaEstado->fetch(PDO::FETCH_ASSOC)["estado"];

}catch(PDOException $e){
    $errores[] = "Ocurrio un error el select de estado..." . $e->getMessage();
}

if($estado == 0){
    $estado = 2;
}else if($estado == 2){
    $estado = 0;
}

try {
    $sqlUpdate = "UPDATE codigos SET estado = :estado WHERE codigo = :codigoSala ";
    $updateEstado = $mysql->getConexion()->prepare($sqlUpdate);
    $updateEstado->bindParam("codigoSala", $codigoSala, PDO::PARAM_INT);
    $updateEstado->bindParam("estado", $estado, PDO::PARAM_INT);
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
