<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['emailAdministrador']) && !empty($_POST['emailAdministrador'])
        && isset($_POST['password']) && !empty($_POST['password'])
    ) {

        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        $emailAdmin = trim($_POST['emailAdministrador']);
        $contrasena = trim($_POST['password']);
        if ($frase = "") {
            $frase = null;
        }

        $errores = [];
        try {

            $consultaSql = "select * from administradores where email = :emailAdmin";
            $datosAministradores = $mysql->getConexion()->prepare($consultaSql);
            $datosAministradores->bindParam("emailAdmin", $emailAdmin, PDO::PARAM_STR);

            $datosAministradores->execute();
            $resultado = $datosAministradores->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {

                // 🔥 VALIDACIÓN AGREGADA: impedir ingreso si está inactivo
                if (isset($resultado['estado']) && $resultado['estado'] == "Inactivo") {
                    echo json_encode([
                        "success" => false,
                        "message" => "El administrador está inactivo, no puede iniciar sesión"
                    ]);
                    exit();
                }

                if (password_verify($contrasena, $resultado['password'])) {

                    session_start();
                    $_SESSION['nombreAdmin'] = $resultado['nombre'];
                    $_SESSION['acceso'] = true;
                    echo json_encode([
                        "success" => true,
                        "message" => "Sesión iniciada"
                    ]);
                    exit();
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "¡Contraseña incorrecta!"
                    ]);
                    exit();
                }
            } else {
                echo json_encode(
                    [
                        "success" => false,
                        "message" => "¡Correo ingresado no existe!"
                    ]
                );
                exit();
            }
        } catch (PDOException $e) {
            $errores[] = "Error en la consulta de traer datos de los ADMIN" . $e->getMessage();
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Todos los campos son obligatorios"
        ]);
        exit();
    }
}
