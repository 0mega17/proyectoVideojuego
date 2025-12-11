<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        isset($_POST['cantidadJugadores']) && !empty($_POST['cantidadJugadores']) &&
        isset($_POST['modoJuego']) && !empty($_POST['modoJuego'])
    ) {

        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();
        $codigo = mt_rand(100000, 999999);

        $cantidadJugadores = $_POST['cantidadJugadores'];
        $modoJuego = $_POST['modoJuego'];

        //  AQUÍ VIENE LA CATEGORÍA
        $categoria = $_POST['categoria'] ?? null;


        // Seleccionar el nombre de la categoria
        try {
            if ($categoria != "") {
                $sqlCategoria = "SELECT nombre FROM categorias WHERE id = :IDcategoria";
                $consultaCategoria = $mysql->getConexion()->prepare($sqlCategoria);
                $consultaCategoria->bindParam("IDcategoria", $categoria);
                $consultaCategoria->execute();
                $nombreCategoria = $consultaCategoria->fetch(PDO::FETCH_ASSOC)["nombre"];
            } else {
                $categoria = 0;
                $nombreCategoria = "General";
            }
        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error al consultar el nombre de la categoria " . $e->getMessage()
            ]);
        }

        // Verificacion de conteo minimo de 10 items para generar el bingo por categorias
        try {
            if ($categoria != "") {
                $sqlConteo = "SELECT COUNT(*) AS conteo FROM categorias_has_composiciones WHERE
                categorias_has_composiciones.categorias_id = :categoriaID";
                $consultaConteo = $mysql->getConexion()->prepare($sqlConteo);
                $consultaConteo->bindParam("categoriaID", $categoria);
                $consultaConteo->execute();
                $conteo = $consultaConteo->fetch(PDO::FETCH_ASSOC)["conteo"];

                if ($conteo < 10) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Debe haber minimo 10 obras asociadas a esa categoria" . "<br>" .  "<strong> Categoria: </strong> $nombreCategoria" . "<br>" . "<strong> Cantidad actual: </strong> $conteo"
                    ]);
                    exit();
                }
            }
        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error al consultar el conteo de las categorias " . $e->getMessage()
            ]);
        }




        try {
            //  AGREGAMOS CATEGORÍA A LA TABLA
            $sql = "INSERT INTO codigos (codigo, estado, categoria_codigo)
                    VALUES (:codigo, 1, :categoria)";

            $insert = $mysql->getConexion()->prepare($sql);

            $insert->bindParam(":codigo", $codigo, PDO::PARAM_STR);

            // GUARDAMOS LA CATEGORÍA
            $insert->bindParam(":categoria", $categoria, PDO::PARAM_STR);

            $insert->execute();

            echo json_encode([
                "success" => true,
                "sala" => $codigo,
                "jugadores" => $cantidadJugadores,
                "modo" => $modoJuego,
                "categoria" => $categoria,
                "nombreCategoria" => $nombreCategoria
            ]);
        } catch (PDOException $e) {
            $error = $e->getMessage();
            echo json_encode([
                "success" => false,
                "message" => "Error al crear sala: " . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Todos los campos son obligatorios"
        ]);
    }
}
