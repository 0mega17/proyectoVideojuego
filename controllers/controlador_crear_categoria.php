<?php

// Verificar dato
if (!isset($_POST["categoria"]) || empty(trim($_POST["categoria"]))) {
    echo json_encode([
        "success" => false,
        "message" => "Debe ingresar el nombre de la categoría"
    ]);
    exit();
}

$categoria = trim($_POST["categoria"]);

require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Insertar categoría
try {
    $sql = "INSERT INTO categorias (nombre) VALUES (:nombre)";
    $consulta = $mysql->getConexion()->prepare($sql);
    $consulta->bindParam(":nombre", $categoria, PDO::PARAM_STR);
    $consulta->execute();

    echo json_encode([
        "success" => true,
        "message" => "Categoría creada correctamente"
    ]);
    exit();
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error al crear categoría: " . $e->getMessage()
    ]);
    exit();
}
