<?php

if (!isset($_POST["IDeliminar"])) {
    echo json_encode([
        "success" => false,
        "message" => "No se recibió el ID a eliminar"
    ]);
    exit();
}

$id = intval($_POST["IDeliminar"]);

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

try {
    $sql = "DELETE FROM categorias WHERE id = :id";
    $consulta = $mysql->getConexion()->prepare($sql);
    $consulta->bindParam(":id", $id, PDO::PARAM_INT);
    $consulta->execute();

    echo json_encode([
        "success" => true,
        "message" => "Categoría eliminada correctamente"
    ]);
    exit();
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error al eliminar: " . $e->getMessage()
    ]);
    exit();
}
