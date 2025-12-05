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
$estado = "Reset";

try{
    $sqlID = "SELECT id FROM codigos WHERE codigo = :codigoSala";
    $consultaID = $mysql->getConexion()->prepare($sqlID);
    $consultaID->bindParam("codigoSala", $codigoSala);
    $consultaID->execute();

    $IDsala = $consultaID->fetch(PDO::FETCH_ASSOC)["id"];
}catch(PDOException $e){

}

try {
    $sqlUpdate = "UPDATE estado_juego SET estado = :estado WHERE codigos_id = :IDsala ";
    $updateEstado = $mysql->getConexion()->prepare($sqlUpdate);
    $updateEstado->bindParam("IDsala", $IDsala);
    $updateEstado->bindParam("estado", $estado);
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
