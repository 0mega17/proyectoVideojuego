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

// Seleccionar todas las tablas de la sala
try {
    $sqlSelectID = "SELECT tablas.id FROM tablas WHERE codigos_codigo = :codigoSala";
    $selectID = $mysql->getConexion()->prepare($sqlSelectID);
    $selectID->bindParam("codigoSala", $codigoSala, PDO::PARAM_INT);
    $selectID->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en el select de la tabla..." . $e->getMessage();
}

// Eliminar todas las casillas de las tablas
while ($fila = $selectID->fetch(PDO::FETCH_ASSOC)) {
    $ID = $fila["id"];
    try {
        $sqlDelete = "DELETE FROM casillas_tablas WHERE tablas_id = :tablaID";
        $DeleteTabla = $mysql->getConexion()->prepare($sqlDelete);
        $DeleteTabla->bindParam("tablaID", $ID, PDO::PARAM_INT);
        $DeleteTabla->execute();
    } catch (PDOException $e) {
        $errores[] = "Ocurrio un error en el delete de la casilla..." . $e->getMessage();
    }
}

// Eliminar todas las tablas
try {
    $sqlDelete = "DELETE FROM tablas WHERE codigos_codigo = :codigoSala";
    $DeleteTabla = $mysql->getConexion()->prepare($sqlDelete);
    $DeleteTabla->bindParam("codigoSala", $codigoSala, PDO::PARAM_INT);
    $DeleteTabla->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en el delete de la tabla..." . $e->getMessage();
}


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

// Eliminar todos los aprendices de la sala
try {
    $sqlTruncate = "DELETE FROM aprendices WHERE codigo_sala = :codigoSala";
    $consultaTruncate = $mysql->getConexion()->prepare($sqlTruncate);
    $consultaTruncate->bindParam("codigoSala", $codigoSala);
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
