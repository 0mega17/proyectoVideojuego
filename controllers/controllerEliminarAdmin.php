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
            $sqlCategorias = "DELETE FROM administradores WHERE id=:IDeliminar";
            $consultaCategorias = $mysql->getConexion()->prepare($sqlCategorias);
            $consultaCategorias->bindParam("IDeliminar", $IDeliminar, PDO::PARAM_INT);
            $consultaCategorias->execute();
        } catch (PDOException $e) {
            $errores[] = "Error al eliminar" . $e->getMessage();
        }


        if(count($errores) == 0){
            echo json_encode([
                "success" => true,
                "message" => "Administrador eliminado"
            ]);
        }
    }
}
