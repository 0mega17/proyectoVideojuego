<?php
session_start();

header("Content-Type: application/json");

require_once "../models/MySQL.php";
$mysql = new MySQL();
$mysql->conectar();
// ahora se debe de validar si el usuarrio tiene datos en la base de datos en base del id 

$idAprendiz = $_SESSION["idAprendiz"];

$sql = "SELECT * FROM aprendices WHERE id = :idAprendiz";
$stmt = $mysql->getConexion()->prepare($sql);
$stmt->bindParam(":idAprendiz", $idAprendiz, PDO::PARAM_INT);
$stmt->execute();
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$resultado) {
    session_unset();
    session_destroy();
    echo json_encode([
        "valido" => false,
        "motivo" => "Sesión inválida. Por favor, inicie sesión nuevamente."
    ]);
    exit();
}


echo json_encode(["valido" => true]);
