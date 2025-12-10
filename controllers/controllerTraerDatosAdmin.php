<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["IDeditar"]) && !empty($_POST["IDeditar"])) {

        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        $IDeditar = intval($_POST["IDeditar"]);
        $errores = [];

        try {

            $sql = "SELECT nombre, email FROM administradores WHERE id = :IDeditar";
            $consultaAdmin = $mysql->getConexion()->prepare($sql);
            $consultaAdmin->bindParam(":IDeditar", $IDeditar, PDO::PARAM_INT);

            $consultaAdmin->execute();
            $datos = $consultaAdmin->fetch(PDO::FETCH_ASSOC);

            if (!$datos) {
                echo json_encode([
                    "success" => false,
                    "message" => "No se encontró el administrador"
                ]);
                exit();
            }

            // RESPUESTA JSON
            header("Content-Type: application/json");
            echo json_encode($datos);
            exit();

        } catch (PDOException $e) {

            echo json_encode([
                "success" => false,
                "message" => "Error al obtener los datos: " . $e->getMessage()
            ]);
            exit();
        }
    }
}
