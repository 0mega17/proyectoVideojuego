<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    header("Content-Type: application/json"); // ← MUY IMPORTANTE

    if (isset($_POST["IDeliminar"]) && !empty($_POST["IDeliminar"])) {
        $IDeliminar = intval($_POST["IDeliminar"]);

        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        $errores = [];

        try {
            $sqlEstadoAdmin = "UPDATE administradores SET estado='Inactivo' WHERE id=:IDeliminar";
            $consultaEstadoAdmins = $mysql->getConexion()->prepare($sqlEstadoAdmin);
            $consultaEstadoAdmins->bindParam("IDeliminar", $IDeliminar, PDO::PARAM_INT);
            $consultaEstadoAdmins->execute();
        } catch (PDOException $e) {
            $errores[] = "Error al eliminar: " . $e->getMessage();
        }

        if (count($errores) === 0) {
            echo json_encode([
                "success" => true,
                "message" => "Administrador eliminado correctamente."
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => $errores
            ]);
        }
        exit; // ← MUY IMPORTANTE: evita enviar basura después
    }

    echo json_encode([
        "success" => false,
        "message" => "ID no recibido."
    ]);
    exit;
}
