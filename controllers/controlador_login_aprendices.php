<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['nombreAprendiz']) && !empty($_POST['nombreAprendiz'])
        && isset($_POST['ficha']) && !empty($_POST['ficha'])
        && isset($_POST['codigoSala']) && !empty($_POST['codigoSala'])
    ) {

        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        $nombre = trim($_POST['nombreAprendiz']);
        $ficha = trim($_POST['ficha']);
        $codigo = trim($_POST['codigoSala']);
        if ($frase = "") {
            $frase = null;
        }

        $errores = [];
        try {
            // primero se hace la consulta sobre si el codigo de ficha ingresado es valido cuando el estado sea 1 es que esta activo y puede entrar 

            $consultaEstadoSala = "select codigo from codigos where estado = 1 and codigo = :codigoIngresado";
            $codigoConsulta = $mysql->getConexion()->prepare($consultaEstadoSala);
            $codigoConsulta->bindParam(":codigoIngresado", $codigo, PDO::PARAM_INT);
            $codigoConsulta->execute();
            $resultadoCodigo = $codigoConsulta->fetch(PDO::FETCH_ASSOC);
            if ($resultadoCodigo) {

                // convertir los datos tanto de la base de datos como el que es ingresado 
                // en la base de datos 
                $codigoBD = (int)$resultadoCodigo['codigo'];
                $codigoAprendiz = (int)$codigo;
                if ($codigoBD === $codigoAprendiz) {

                    $insertAprendiz = "INSERT INTO aprendices (nombre, ficha) VALUES (:nombre, :ficha)";

                    $resultado = $mysql->getConexion()->prepare($insertAprendiz);
                    $resultado->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                    $resultado->bindParam(":ficha", $ficha, PDO::PARAM_STR);

                    $resultado->execute();

                    session_start();
                    $_SESSION["codigoSala"] = $codigoBD;
                    $_SESSION["accesoAprendiz"] = true;
                    if ($resultado) {
                        echo json_encode([
                            "validacion" => true
                        ]);
                    } else {
                        echo json_encode([
                            "validacion" => false,
                            "mensaje" => "No se pudo ingresar a la sala"
                        ]);
                    }
                } else {
                    echo json_encode([
                        "validacion" => false,
                        "mensaje" => "Codigo de sala incorrecto"
                    ]);
                }
            } else {
                echo json_encode([
                    "validacion" => false,
                    "mensaje" => "Codigo de sala incorrecto"
                ]);
            }
        } catch (PDOException $e) {
            $errores[] = "Error en la consulta de traer datos de los ADMIN" . $e->getMessage();
        }
    }else{
        echo json_encode([
            "validacion" => false,
            "mensaje" => "Todos los campos son obligatorios"
        ]);
    }
}
