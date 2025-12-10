<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        isset($_POST["nombre"]) && !empty($_POST["nombre"]) &&
        isset($_POST["email"]) && !empty($_POST["email"]) &&
        isset($_POST["pass"]) && !empty($_POST["pass"])
    ) {

        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        //! Sanatización
        $nombre = filter_var(trim($_POST["nombre"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email  = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $pass   = password_hash($_POST["pass"], PASSWORD_BCRYPT);

        //! Validación email
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "success" => false,
                "message" => "Ingrese un email válido"
            ]);
            exit();
        }

        //! Verificar email repetido
        try {

            $stmt = $mysql->getConexion()->prepare("SELECT 1 FROM administradores WHERE email = :email");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
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

        //! Insertar Administrador
        try {

            $sqlAdmins = "INSERT INTO administradores (nombre, email, password,estado)
                          VALUES (:nombre, :email, :pass,'Activo')";

            $insertAdministradores = $mysql->getConexion()->prepare($sqlAdmins);

            $insertAdministradores->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $insertAdministradores->bindParam(":email", $email, PDO::PARAM_STR);
            $insertAdministradores->bindParam(":pass", $pass, PDO::PARAM_STR);

            $insertAdministradores->execute();

        } catch (PDOException $e) {

            echo json_encode([
                "success" => false,
                "message" => "Error al crear un administrador: " . $e->getMessage()
            ]);
            exit();
        }

        //! Éxito
        echo json_encode([
            "success" => true,
            "message" => "¡Administrador Creado Exitosamente!"
        ]);
        exit();

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Faltan campos por rellenar..."
        ]);
        exit();
    }
}
