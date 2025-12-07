<?php

if (!isset($_POST["IDeditar"])) {
    echo json_encode([
        "success" => false,
        "message" => "No se recibió el ID"
    ]);
    exit();
}

$id = $_POST["IDeditar"];

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

try {
    $sql = "SELECT * FROM categorias WHERE id = :id";
    $consulta = $mysql->getConexion()->prepare($sql);
    $consulta->bindParam(":id", $id, PDO::PARAM_INT);
    $consulta->execute();

    $categoria = $consulta->fetch(PDO::FETCH_ASSOC);

    if (!$categoria) {
        echo json_encode([
            "success" => false,
            "message" => "Categoría no encontrada"
        ]);
        exit();
    }

    echo json_encode([
        "success" => true,
        "datos" => $categoria
    ]);
    exit();
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en la consulta: " . $e->getMessage()
    ]);
    exit();
}
