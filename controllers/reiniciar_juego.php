<?php

// Conexion a la base de datos
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$errores = [];

// Codigo de la sala
$codigoSala = $_POST["codigoSala"];
$accion = "reiniciar";

// Seleccionar la accion actual
try {
    $sqlAccion = "SELECT accion FROM codigos WHERE codigo = :codigoSala";
    $consultaAccion = $mysql->getConexion()->prepare($sqlAccion);
    $consultaAccion->bindParam("codigoSala", $codigoSala, PDO::PARAM_INT);
    $consultaAccion->execute();
    $accion = $consultaAccion->fetch(PDO::FETCH_ASSOC)["accion"];
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error el select de estado..." . $e->getMessage();
}

// Numerar las ccciones para actualizar
if ($accion == null || $accion == "jugar") {
    $accion = "reiniciar1";
} else {
    $num = substr($accion, 9, 1);
    $accion = "reiniciar" . ($num + 1);
}

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
        "message" => "El juego fue reiniciado exitosamente"
    ]);
}
