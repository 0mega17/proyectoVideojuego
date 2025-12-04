<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["IDeliminar"]) && !empty($_POST["IDeliminar"])) {
        $IDeliminar = intval($_POST["IDeliminar"]);

        // Conexion con la base de datos
        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // Errores
        $errores = [];

        // consulta eliminar categorias de obras literarias
        try {
            $sqlCategorias = "DELETE FROM categorias_has_composiciones WHERE composiciones_id = :IDeliminar";
            $consultaCategorias = $mysql->getConexion()->prepare($sqlCategorias);
            $consultaCategorias->bindParam("IDeliminar", $IDeliminar);
            $consultaCategorias->execute();
        } catch (PDOException $e) {
            $errores[] = "Error en categorias... " . $e->getMessage();
        }


        // Consulta eliminar obras literarias 
        try {
            $sqlObras = "DELETE FROM composiciones WHERE id = :IDeliminar";
            $consultaObras = $mysql->getConexion()->prepare($sqlObras);
            $consultaObras->bindParam("IDeliminar", $IDeliminar);
            $consultaObras->execute();
        } catch (PDOException $e) {
            $errores[] = "Error en composiciones..." . $e->getMessage();
        }


        if(count($errores) == 0){
            echo json_encode([
                "success" => true,
                "message" => "Obra literaria eliminada"
            ]);
        }
    }
}
