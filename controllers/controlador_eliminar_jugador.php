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


        // Seleccionar el ID de la tabla a eliminar
        try {
            $sqlSelectID = "SELECT tablas.id FROM tablas WHERE aprendices_id = :IDeliminar";
            $selectID = $mysql->getConexion()->prepare($sqlSelectID);
            $selectID->bindParam("IDeliminar", $IDeliminar, PDO::PARAM_INT);
            $selectID->execute();
            $IDtabla = $selectID->fetch(PDO::FETCH_ASSOC)["id"];
        } catch (PDOException $e) {
            $errores[] = "Ocurrio un error en el select de la tabla..." . $e->getMessage();
        }

        // Eliminar las casillas de la tabla seleccionada
        try {
            $sqlDelete = "DELETE FROM casillas_tablas WHERE tablas_id = :tablaID";
            $DeleteTabla = $mysql->getConexion()->prepare($sqlDelete);
            $DeleteTabla->bindParam("tablaID", $IDtabla, PDO::PARAM_INT);
            $DeleteTabla->execute();
        } catch (PDOException $e) {
            $errores[] = "Ocurrio un error en el delete de las casillas..." . $e->getMessage();
        }

        // Eliminar la tabla seleccionada
        try {
            $sqlDelete = "DELETE FROM tablas WHERE aprendices_id = :IDeliminar";
            $DeleteTabla = $mysql->getConexion()->prepare($sqlDelete);
            $DeleteTabla->bindParam("IDeliminar", $IDeliminar, PDO::PARAM_INT);
            $DeleteTabla->execute();
        } catch (PDOException $e) {
            $errores[] = "Ocurrio un error en el delete de la tabla..." . $e->getMessage();
        }


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
