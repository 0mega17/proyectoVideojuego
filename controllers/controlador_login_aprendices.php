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

                    // Verificar cantidad de jugadores en la sala
                    $consultaCantidad = "SELECT COUNT(*) as total_jugadores FROM aprendices WHERE codigo_sala = :codigoSala";
                    $stmtCantidad = $mysql->getConexion()->prepare($consultaCantidad);
                    $stmtCantidad->bindParam(":codigoSala", $codigoBD, PDO::PARAM_INT);
                    $stmtCantidad->execute();
                    $totalJugadores = $stmtCantidad->fetch(PDO::FETCH_ASSOC)['total_jugadores'];

                    // Obtener cantidad máxima de la sala
                    $consultaMax = "SELECT cantidad_jugadores FROM codigos WHERE codigo = :codigoSala";
                    $stmtMax = $mysql->getConexion()->prepare($consultaMax);
                    $stmtMax->bindParam(":codigoSala", $codigoBD, PDO::PARAM_INT);
                    $stmtMax->execute();
                    $maxJugadores = $stmtMax->fetch(PDO::FETCH_ASSOC)['cantidad_jugadores'];

                    if ($totalJugadores >= $maxJugadores) {
                        echo json_encode([
                            "validacion" => false,
                            "mensaje" => "La sala ya está llena. Cantidad máxima de jugadores: $maxJugadores"
                        ]);
                        exit();
                    }

                    $insertAprendiz = "INSERT INTO aprendices (nombre, ficha, codigo_sala) VALUES (:nombre, :ficha, :codigoSala)";

                    $resultado = $mysql->getConexion()->prepare($insertAprendiz);
                    $resultado->bindParam(":nombre", $nombre, PDO::PARAM_STR);
                    $resultado->bindParam(":ficha", $ficha, PDO::PARAM_STR);
                    $resultado->bindParam(":codigoSala", $codigoBD, PDO::PARAM_INT);

                    $resultado->execute();
                    // se deben de traer los datos del aprendiz para poder tener el id de el y ponerlo en SESSION
                    $traerDatos = "SELECT * FROM aprendices ORDER BY id DESC LIMIT 1";
                    $resultadoDatosUsuario = $mysql->getConexion()->prepare($traerDatos);
                    $resultadoDatosUsuario->execute();
                    $datos = $resultadoDatosUsuario->fetch(PDO::FETCH_ASSOC);

                    // Insertar la tabla de cada aprendiz
                    try {
                        $conteo = 0;
                        $sql = "INSERT INTO tablas (aprendices_id, codigos_codigo, conteo) VALUES (:IDaprendiz, :codigo, :conteo)";
                        $insertTabla = $mysql->getConexion()->prepare($sql);
                        $insertTabla->bindParam("IDaprendiz", $datos["id"], PDO::PARAM_INT);
                        $insertTabla->bindParam("codigo", $codigo, PDO::PARAM_INT);
                        $insertTabla->bindParam("conteo", $conteo, PDO::PARAM_INT);
                        $insertTabla->execute();
                    } catch (PDOException $e) {
                        $error = $e->getMessage();
                    }

                    session_start();
                    $_SESSION["idAprendiz"] = $datos['id'];
                    $_SESSION["codigoSala"] = $codigoBD;
                    $_SESSION["accesoAprendiz"] = true;
                    $_SESSION["nombreAprendiz"] = $nombre;
                    $_SESSION["fichaAprendiz"] = $ficha;
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
                    "mensaje" => "Codigo de sala caducado"
                ]);
            }
        } catch (PDOException $e) {
            $errores[] = "Error en la consulta de traer datos de los ADMIN" . $e->getMessage();
        }
    } else {
        echo json_encode([
            "validacion" => false,
            "mensaje" => "Todos los campos son obligatorios"
        ]);
    }
}
