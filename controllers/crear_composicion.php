<?php


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
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
        $titulo = trim($_POST["titulo"]);
        $autor = trim($_POST["autor"]);
        $frase = trim($_POST["frase"]);
        $categorias = $_POST["categorias"];
        $tipoMaterial = intval($_POST["tipo"]);

        if($frase = ""){
            $frase = null;
        }

        $errores = [];

        try {
            $sqlComposiciones = "INSERT INTO composiciones(titulo, autor, frase, tipo_material_id) VALUES(:titulo,
        :autor, :frase, :tipoMaterial)";
            $insertComposiciones = $mysql->getConexion()->prepare($sqlComposiciones);
            $insertComposiciones->bindParam("titulo", $titulo, PDO::PARAM_STR);
            $insertComposiciones->bindParam("autor", $autor, PDO::PARAM_STR);
            $insertComposiciones->bindParam("frase", $frase, PDO::PARAM_STR);
            $insertComposiciones->bindParam("tipoMaterial", $tipoMaterial, PDO::PARAM_INT);
            $insertComposiciones->execute();
        } catch (PDOException $e) {
            $errores[] = "Error en el insert de composiciones" . $e->getMessage();
        }

        try{
            $sqlID = "SELECT MAX(id) as IDmaximo FROM composiciones";
            $selectID = $mysql->getConexion()->prepare($sqlID);
            $selectID->execute();

            $IDcomposicion = $selectID->fetch(PDO::FETCH_ASSOC)["IDmaximo"];
        }catch(PDOException $e){
            $errores[] = "Error en la consulta ID " . $e->getMessage();
        }
      

        try{
            foreach($categorias as $cat){
                $IDcategoria = $cat;
                $sqlCategorias = "INSERT INTO categorias_has_composiciones(categorias_id, composiciones_id)
                VALUES(:IDcategoria, :IDcomposicion)";

                $insertCategorias = $mysql->getConexion()->prepare($sqlCategorias);
                $insertCategorias->bindParam("IDcategoria", $IDcategoria, PDO::PARAM_INT);
                $insertCategorias->bindParam("IDcomposicion", $IDcomposicion, PDO::PARAM_INT);

                $insertCategorias->execute();


            }
        }catch(PDOException $e){
            $errores[] = "Error en el insert de categorias" . $e->getMessage();
        }


        if(count($errores) == 0){
            echo json_encode([
                "success" => true,
                "message" => "¡Composicion agregada exitosamente!"
            ]);
            exit();
        }else{
            echo json_encode([
                "success" => false,
                "message" => "Ocurrio un error..."
            ]);
            exit();
        }
    }else{
        echo json_encode([
            "success" => false,
            "message" => "Faltan campos por rellenar..."
        ]);
        exit();
    }
}
