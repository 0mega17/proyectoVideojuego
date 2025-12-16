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

$consultarObrasAsociadas = "SELECT COUNT(*) as total FROM categorias_has_composiciones WHERE categorias_id = :id";
    $consultaObras = $mysql->getConexion()->prepare($consultarObrasAsociadas);
    $consultaObras->bindParam(":id", $id, PDO::PARAM_INT);
    $consultaObras->execute();
    $resultados = $consultaObras->fetch(PDO::FETCH_ASSOC);

    if ($resultados['total'] > 0) {
        echo json_encode([
            "success" => false,
            "message" => "No se puede eliminar la categoría porque tiene obras asociadas."
        ]);
        exit();
    }

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
