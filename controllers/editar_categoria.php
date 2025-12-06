<?php

if (!isset($_POST["IDeditar"])) {
    echo json_encode([
        "success" => false,
        "message" => "No se recibió el ID de la categoría"
    ]);
    exit();
}

if (!isset($_POST["categoria"]) || empty(trim($_POST["categoria"]))) {
    echo json_encode([
        "success" => false,
        "message" => "Debe ingresar el nombre de la categoría"
    ]);
    exit();
}

$id = intval($_POST["IDeditar"]);
$categoria = trim($_POST["categoria"]);

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

try {
    $sql = "UPDATE categorias SET nombre = :nombre WHERE id = :id";
    $consulta = $mysql->getConexion()->prepare($sql);
    $consulta->bindParam(":nombre", $categoria, PDO::PARAM_STR);
    $consulta->bindParam(":id", $id, PDO::PARAM_INT);
    $consulta->execute();

    echo json_encode([
        "success" => true,
        "message" => "Categoría actualizada correctamente"
    ]);
    exit();
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error al actualizar categoría: " . $e->getMessage()
    ]);
    exit();
}
