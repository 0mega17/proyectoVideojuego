<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["id"]) && !empty($_POST["id"])) {

        $id = intval($_POST["id"]);
        $codigoSala = intval($_POST["codigo"]);

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


        // Seleccionar todas las tablas de la sala
        try {
            $sqlSelectID = "SELECT tablas.id FROM tablas WHERE codigos_codigo = :codigoSala";
            $selectID = $mysql->getConexion()->prepare($sqlSelectID);
            $selectID->bindParam("codigoSala", $codigoSala, PDO::PARAM_INT);
            $selectID->execute();
        } catch (PDOException $e) {
            $errores[] = "Ocurrio un error en el select de la tabla..." . $e->getMessage();
        }

        // Eliminar todas las casillas de las tablas
        while ($fila = $selectID->fetch(PDO::FETCH_ASSOC)) {
            $ID = $fila["id"];
            try {
                $sqlDelete = "DELETE FROM casillas_tablas WHERE tablas_id = :tablaID";
                $DeleteTabla = $mysql->getConexion()->prepare($sqlDelete);
                $DeleteTabla->bindParam("tablaID", $ID, PDO::PARAM_INT);
                $DeleteTabla->execute();
            } catch (PDOException $e) {
                $errores[] = "Ocurrio un error en el delete de la casilla..." . $e->getMessage();
            }
        }

        // Eliminar todas las tablas
        try {
            $sqlDelete = "DELETE FROM tablas WHERE codigos_codigo = :codigoSala";
            $DeleteTabla = $mysql->getConexion()->prepare($sqlDelete);
            $DeleteTabla->bindParam("codigoSala", $codigoSala, PDO::PARAM_INT);
            $DeleteTabla->execute();
        } catch (PDOException $e) {
            $errores[] = "Ocurrio un error en el delete de la tabla..." . $e->getMessage();
        }

        // Eliminar aprendiz
        try {
            $sql = "DELETE FROM aprendices WHERE codigo_sala = :codigoSala";
            $consulta = $mysql->getConexion()->prepare($sql);
            $consulta->bindParam(":codigoSala", $codigoSala, PDO::PARAM_INT);
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