<?php

// Conexion a la base de datos
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

session_start();

$IDaprendiz = $_SESSION["idAprendiz"];
$tablaGuardada = json_decode($_POST["tablaGuardada"]);

// Seleccionar el ID de la tabla insertado
try {
    $sql = "SELECT id FROM tablas WHERE aprendices_id = :IDaprendiz";
    $selectTabla = $mysql->getConexion()->prepare($sql);
    $selectTabla->bindParam("IDaprendiz", $IDaprendiz, PDO::PARAM_INT);
    $selectTabla->execute();
    $IDtabla = $selectTabla->fetch(PDO::FETCH_ASSOC)["id"]; 
} catch (PDOException $e) {
    $error = $e->getMessage();
}

$posArray = 0;

for ($r = 0; $r < 5; $r++) {
    for ($c = 0; $c < 5; $c++) {
        $contenido = $tablaGuardada[$posArray];
        try {
            $sql = "INSERT INTO casillas_tablas (tablas_id, contenido, fila, columna)
    VALUES (:tabla_id, :contenido, :fila, :columna)";
            $insertCasilla = $mysql->getConexion()->prepare($sql);
            $insertCasilla->bindParam("tabla_id", $IDtabla, PDO::PARAM_INT);
            $insertCasilla->bindParam("contenido", $contenido, PDO::PARAM_STR);
            $insertCasilla->bindParam("fila", $r, PDO::PARAM_INT);
            $insertCasilla->bindParam("columna", $c, PDO::PARAM_INT);
            $insertCasilla->execute();
            $posArray++;
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
}


echo json_encode([
    "success" => true,
    "message" => "insertado..."
]);
