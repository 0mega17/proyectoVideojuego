<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["IDeditar"]) || !empty($_POST["IDeditar"])) {
        // Conexion a la base de datos
        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        $IDeditar = intval($_POST["IDeditar"]);
        $errores = [];

        try {

            $sql = "SELECT * FROM composiciones WHERE id = :IDeditar";
            $consultaComposiciones = $mysql->getConexion()->prepare($sql);
            $consultaComposiciones->bindParam("IDeditar", $IDeditar);

            if ($consultaComposiciones->execute()) {
                $datosComposicion = $consultaComposiciones->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            $errores[] = "Error en los datos de composicion: "  . $e->getMessage();
            exit();
        }

        try {
            $sql = "SELECT * FROM categorias_has_composiciones WHERE composiciones_id = :IDeditar";
            $consultaCategorias = $mysql->getConexion()->prepare($sql);
            $consultaCategorias->bindParam("IDeditar", $IDeditar);
            $consultaCategorias->execute();
        } catch (PDOException $e) {
            $errores[] = "Error en los datos de la tbl pivote: "  . $e->getMessage();
            exit();
        }

        $categorias = [];

        while ($fila = $consultaCategorias->fetch(PDO::FETCH_ASSOC)) {
            $categoriaID = $fila["categorias_id"];
            $categorias[] = $categoriaID;
        }

        header("ContentType:application/json");
        echo json_encode([
            "composiciones" => $datosComposicion,
            "categorias" => $categorias
        ]);
    }
}
