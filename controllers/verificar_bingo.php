<?php


// Conexion a la base de datos
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$errores = [];

// Captura del arreglo de las balotas que ya salieron
$arregloBalotas = json_decode($_POST["arregloBalotas"], true);



session_start();
$codigo = $_SESSION["codigoSala"];
$tablas = [];
// Seleccionar todas las tablas con su contenido
try {
    $sql = "SELECT tablas.id, tablas.aprendices_id, tablas.conteo, casillas_tablas.contenido FROM tablas JOIN casillas_tablas ON casillas_tablas.tablas_id = tablas.id WHERE tablas.codigos_codigo = :codigo";
    $consultaTablas = $mysql->getConexion()->prepare($sql);
    $consultaTablas->bindParam("codigo", $codigo);
    $consultaTablas->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en composiciones..." . $e->getMessage();
}

// Resetear el contador  de la sala
try {
    $reinicio = 0;
    $sql = "UPDATE tablas SET conteo = :reinicio WHERE tablas.codigos_codigo = :codigo";
    $consultaReset = $mysql->getConexion()->prepare($sql);
    $consultaReset->bindParam("reinicio", $reinicio);
    $consultaReset->bindParam("codigo", $codigo);
    $consultaReset->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en reset..." . $e->getMessage();
}

if ($arregloBalotas) {
    while ($fila = $consultaTablas->fetch(PDO::FETCH_ASSOC)) {
        for ($i = 0; $i < count($arregloBalotas); $i++) {
            $balota = $arregloBalotas[$i]["balota"];
            $tablaCasilla = $fila["contenido"];
            if ($balota == $tablaCasilla) {
                try {
                    $acumulador = 1;
                    $IDtabla = intval($fila["id"]);
                    $sqlConteo = "UPDATE tablas SET conteo = conteo + :acumulador  WHERE id = :IDtabla";
                    $consultaConteo = $mysql->getConexion()->prepare($sqlConteo);
                    $consultaConteo->bindParam("IDtabla", $IDtabla, PDO::PARAM_INT);
                    $consultaConteo->bindParam("acumulador", $acumulador, PDO::PARAM_INT);
                    $consultaConteo->execute();
                } catch (PDOException $e) {
                    $erorr = $e->getMessage();
                    echo json_encode([
                        "success" => false,
                        "message" => "error en el conteo..."
                    ]);
                }
            }
        }
    }
}


// Seleccionar el conteo de cada tabla
try {
    $sql = "SELECT id, conteo, (SELECT nombre FROM aprendices WHERE aprendices.id = tablas.aprendices_id)
    as nombre_aprendiz FROM tablas WHERE codigos_codigo = :codigo ORDER BY conteo DESC ";
    $tablasTotal = $mysql->getConexion()->prepare($sql);
    $tablasTotal->bindParam("codigo", $codigo);
    $tablasTotal->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en reset..." . $e->getMessage();
}

$tablasConteo = [];

while ($fila = $tablasTotal->fetch(PDO::FETCH_ASSOC)) {
    $tablasConteo[] = $fila;
}

echo json_encode([
    "success" => true,
    "tablasConteo" => $tablasConteo
]);
