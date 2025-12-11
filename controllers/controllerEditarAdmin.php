<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        isset($_POST["nombre"]) && !empty($_POST["nombre"])
        && isset($_POST["email"]) && !empty($_POST["email"])
    ) {

        require_once '../models/MySQL.php';

        $mysql = new MySQL();
        $mysql->conectar();

        // Capturar usuario logeado
        $IdUsuario = $_SESSION['IdUsuario'];

        // Sanitización
        $nombre = trim($_POST["nombre"]);
        $email = trim($_POST["email"]);

        // Validación email
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese un email válido"
            ]);
            exit();
        }

        // ================================
        // VERIFICAR EMAIL REPETIDO (PDO)
        // ================================
        try {

            $stmt = $mysql->getConexion()->prepare(
                "SELECT 1 
     FROM administradores 
     WHERE email = :email AND id != :id"
            );
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":id", $IdUsuario, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->fetchColumn()) {
                echo json_encode([
                    "success" => false,
                    "message" => "Ingrese un email que no esté repetido"
                ]);
                exit();
            }
        } catch (PDOException $e) {

            echo json_encode([
                "success" => false,
                "message" => "Error al verificar email: " . $e->getMessage()
            ]);
            exit();
        }

        // =========================================
        // OBTENER CONTRASEÑA ACTUAL (VERSIÓN PDO)
        // =========================================
        try {
            $stmt = $mysql->getConexion()->prepare("SELECT password FROM administradores WHERE id = :id");
            $stmt->bindParam(":id", $IdUsuario, PDO::PARAM_INT);
            $stmt->execute();
            $passwordBD = $stmt->fetch(PDO::FETCH_ASSOC)["password"];
        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error al obtener contraseña: " . $e->getMessage()
            ]);
            exit();
        }

        // Por defecto mantener la actual
        $newPassword = $passwordBD;
        $cambiarPassword = false;

        // ¿Quiere cambiar contraseña?
        if (
            isset($_POST["oldPassword"]) && !empty($_POST["oldPassword"])
            && isset($_POST["newPassword"]) && !empty($_POST["newPassword"])
        ) {
            $cambiarPassword = true;
            $newPassword = password_hash($_POST["newPassword"], PASSWORD_BCRYPT);
        }

        if (empty($_POST["oldPassword"])) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese su contraseña actual para actualizar su perfil"
            ]);
            exit();
        }

        // Verificar contraseña actual
        if (!password_verify($_POST["oldPassword"], $passwordBD)) {
            echo json_encode([
                "success" => false,
                "message" => "La contraseña actual es incorrecta"
            ]);
            exit();
        }

        // ========================================
        // ACTUALIZAR USUARIO (VERSIÓN PDO)
        // ========================================
        try {
            $stmt = $mysql->getConexion()->prepare(
                "UPDATE administradores 
                 SET nombre = :nombre,email = :email, password = :password 
                 WHERE id = :id"
            );

            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $newPassword, PDO::PARAM_STR);
            $stmt->bindParam(":id", $IdUsuario, PDO::PARAM_INT);

            $update = $stmt->execute();

            if ($update) {
                echo json_encode([
                    "success" => true,
                    "message" => "Perfil actualizado exitosamente"
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Ocurrió un error al actualizar el perfil"
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error al actualizar perfil: " . $e->getMessage()
            ]);
            exit();
        }

        $mysql->desconectar();
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Faltan campos por rellenar, Intentelo de nuevo"
        ]);
        exit();
    }
}
