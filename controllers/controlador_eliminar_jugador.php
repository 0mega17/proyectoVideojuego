<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["IDeliminar"]) && !empty($_POST["IDeliminar"])) {

        $IDeliminar = intval($_POST["IDeliminar"]);

        // Conexión con la base de datos
        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // Arreglo para errores
        $errores = [];

        // Eliminar aprendiz
        try {
            $sql = "DELETE FROM aprendices WHERE id = :IDeliminar";
            $consulta = $mysql->getConexion()->prepare($sql);
            $consulta->bindParam(":IDeliminar", $IDeliminar, PDO::PARAM_INT);
            $consulta->execute();
        } catch (PDOException $e) {
            $errores[] = "Error al eliminar: " . $e->getMessage();
        }

        // Respuesta
        if (count($errores) == 0) {
            echo json_encode([
                "success" => true,
                "message" => "Aprendiz eliminado correctamente"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No se pudo eliminar el aprendiz",
                "errores" => $errores
            ]);
        }
    }
}
