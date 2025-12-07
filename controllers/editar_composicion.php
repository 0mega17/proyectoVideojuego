<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["IDeditar"]) && !empty($_POST["IDeditar"]) &&
        isset($_POST["titulo"]) && !empty($_POST["titulo"]) &&
        isset($_POST["autor"]) && !empty($_POST["autor"]) &&
        isset($_POST["tipo"]) && !empty($_POST["tipo"]) &&
        isset($_POST["categorias"]) && !empty($_POST["categorias"])
    ) {

        // CONEXION A LA BASE DE DATOS
        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        // CAPTURAR LOS DATOS ENVIADOS
        $IDcomposicion = intval($_POST["IDeditar"]);
        $titulo = trim($_POST["titulo"]);
        $autor = trim($_POST["autor"]);
        $frase = trim($_POST["frase"]);
        $categorias = $_POST["categorias"];
        $tipoMaterial = intval($_POST["tipo"]);

        $errores = [];

        // REALIZAR EL UPDATE
        try {
            $sqlComposiciones = "UPDATE composiciones SET titulo = :titulo, autor = :autor,
            frase = :frase, tipo_material_id = :tipoMaterial WHERE id = :IDeditar";
            $insertComposiciones = $mysql->getConexion()->prepare($sqlComposiciones);
            $insertComposiciones->bindParam("IDeditar", $IDcomposicion);
            $insertComposiciones->bindParam("titulo", $titulo, PDO::PARAM_STR);
            $insertComposiciones->bindParam("autor", $autor, PDO::PARAM_STR);
            $insertComposiciones->bindParam("frase", $frase, PDO::PARAM_STR);
            $insertComposiciones->bindParam("tipoMaterial", $tipoMaterial, PDO::PARAM_INT);
            $insertComposiciones->execute();
        } catch (PDOException $e) {
            $errores[] = "Error en el update de composiciones" . $e->getMessage();
        }

        // ELIMINAR TODAS LAS CATEGORIAS ASOCIADAS A LA COMPOSICION
        try {
            $sqlDelete = "DELETE FROM categorias_has_composiciones WHERE composiciones_id = :IDeditar";
            $deleteCategorias = $mysql->getConexion()->prepare($sqlDelete);
            $deleteCategorias->bindParam("IDeditar", $IDcomposicion);
            $deleteCategorias->execute();
        } catch (PDOException $e) {
            $errores[] = "Error en el delete de categorias" . $e->getMessage();
        }

        // INSERTAR TODAS LAS CATEGORIAS DEL ARREGLO CON LA ULTIMA COMPOSICION
        try {
            foreach ($categorias as $cat) {
                $IDcategoria = intval($cat);
                $sqlCategorias = "INSERT INTO categorias_has_composiciones(categorias_id, composiciones_id)
                VALUES(:IDcategoria, :IDcomposicion)";

                $insertCategorias = $mysql->getConexion()->prepare($sqlCategorias);
                $insertCategorias->bindParam("IDcategoria", $IDcategoria, PDO::PARAM_INT);
                $insertCategorias->bindParam("IDcomposicion", $IDcomposicion, PDO::PARAM_INT);

                $insertCategorias->execute();
            }
        } catch (PDOException $e) {
            $errores[] = "Error en el insert de categorias" . $e->getMessage();
        }



        if (count($errores) == 0) {
            echo json_encode([
                "success" => true,
                "message" => "¡Composicion editada exitosamente!"
            ]);
            exit();
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Ocurrio un error..."
            ]);
            exit();
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Faltan campos por rellenar..."
        ]);
        exit();
    }
}
