<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id"]) && !empty($_POST["id"])) {

        $id = intval($_POST["id"]);

        // Conexión con la base de datos
        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // Arreglo para errores
        $errores = [];

        // cambiar el estado de la sala a 0 lo cual sifnifica que esta eliminada
        try {
            $sql =  "UPDATE codigos SET estado = 0 WHERE id = :id";
            $consulta = $mysql->getConexion()->prepare($sql);
            $consulta->bindParam(":id", $id, PDO::PARAM_INT);
            $consulta->execute();
        } catch (PDOException $e) {
            $errores[] = "Error al eliminar: " . $e->getMessage();
        }

        // Respuesta
        if (count($errores) == 0) {
            echo json_encode([
                "success" => true,
                "message" => "Sala eliminada correctamente"
            ]);
            exit();
        } else {
            echo json_encode([
                "success" => false,
                "message" => "No se pudo eliminar la sala",
                "errores" => $errores
            ]);
            exit();
        }
    }


}


?>