<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["IDreintegrar"]) && !empty($_POST["IDreintegrar"])) {
        $IDreintegrar = intval($_POST["IDreintegrar"]);

        // Conexion con la base de datos
        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // Errores
        $errores = [];

        // consulta eliminar categorias de obras literarias
        try {
            $sqlEstadoAdmin = "UPDATE administradores SET estado='Activo' WHERE id=:IDreintegrar";
            $consultaEstadoAdmins = $mysql->getConexion()->prepare($sqlEstadoAdmin);
            $consultaEstadoAdmins->bindParam("IDreintegrar", $IDreintegrar, PDO::PARAM_INT);
            $consultaEstadoAdmins->execute();
        } catch (PDOException $e) {
            $errores[] = "Error al reintegrar" . $e->getMessage();
        }


        if(count($errores) == 0){
            echo json_encode([
                "success" => true,
                "message" => "Administrador Reintegrado"
            ]);
        }
    }
}