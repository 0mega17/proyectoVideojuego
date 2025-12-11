<?php
session_start();

header("Content-Type: application/json");


if (!isset($_SESSION["accesoAprendiz"]) || $_SESSION["accesoAprendiz"] !== true) {
    echo json_encode([
        "valido" => false,
        "motivo" => "sesion_no_valida"
    ]);
    exit();
}


if (!isset($_SESSION["codigoSala"])) {
    echo json_encode([
        "valido" => false,
        "motivo" => "sala_no_existente"
    ]);
    exit();
}

require_once "../models/MySQL.php";
$mysql = new MySQL();
$mysql->conectar();

$codigoSala = $_SESSION["codigoSala"];

$sql = "SELECT * FROM codigos WHERE codigo = :codigo";
$stmt = $mysql->getConexion()->prepare($sql);
$stmt->bindParam(":codigo", $codigoSala, PDO::PARAM_INT);
$stmt->execute();
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$resultado) {
    echo json_encode([
        "valido" => false,
        "motivo" => "sala_eliminada"
    ]);
    exit();
}


echo json_encode(["valido" => true]);
