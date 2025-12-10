<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        isset($_POST["IDeditar"]) && !empty($_POST["IDeditar"]) &&
        isset($_POST["nombre"]) && !empty($_POST["nombre"]) &&
        isset($_POST["email"]) && !empty($_POST["email"])
    ) {

        require_once '../models/MySQL.php';
        $mysql = new MySQL();
        $mysql->conectar();

        //! Sanitización
        $IDadmin = intval($_POST["IDeditar"]);
        $nombre  = trim($_POST["nombre"]);
        $email   = trim($_POST["email"]);

        $pass    = !empty($_POST["pass"])
            ? password_hash($_POST["pass"], PASSWORD_BCRYPT)
            : null;

        $errores = [];

        //! Validación de email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            echo json_encode([
                "success" => false,
                "message" => "Ingrese un email válido"
            ]);
            exit();
        }

        // ============================
        // VALIDAR QUE EL EMAIL NO SE REPITA (EXCEPTO EL MISMO ADMIN)
        // ============================
        try {
            $sql = "SELECT id FROM administradores WHERE email = :email AND id != :id";
            $stmt = $mysql->getConexion()->prepare($sql);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":id", $IDadmin, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->fetch()) {
                echo json_encode([
                    "success" => false,
                    "message" => "El email ya está registrado por otro administrador"
                ]);
                exit();
            }

        } catch (PDOException $e) {

            echo json_encode([
                "success" => false,
                "message" => "Error al verificar el email: " . $e->getMessage()
            ]);
            exit();
        }

        // ============================
        // ACTUALIZAR ADMIN
        // ============================
        try {

            if ($pass === null) {
                // Si NO cambia contraseña
                $sql = "UPDATE administradores SET nombre=:nombre, email=:email WHERE id=:IDeditar";
            } else {
                // Si sí cambia contraseña
                $sql = "UPDATE administradores SET nombre=:nombre, email=:email, password=:pass WHERE id=:IDeditar";
            }

            $stmt2 = $mysql->getConexion()->prepare($sql);

            $stmt2->bindParam("nombre", $nombre, PDO::PARAM_STR);
            $stmt2->bindParam("email", $email, PDO::PARAM_STR);
            $stmt2->bindParam("IDeditar", $IDadmin, PDO::PARAM_INT);

            if ($pass !== null) {
                $stmt2->bindParam(":pass", $pass, PDO::PARAM_STR);
            }

            $stmt2->execute();

        } catch (PDOException $e) {

            echo json_encode([
                "success" => false,
                "message" => "Error al actualizar administrador: " . $e->getMessage()
            ]);
            exit();
        }

        // ÉXITO
        echo json_encode([
            "success" => true,
            "message" => "¡Administrador actualizado exitosamente!"
        ]);
        exit();
    }

    // FALTAN CAMPOS
    echo json_encode([
        "success" => false,
        "message" => "Faltan campos por rellenar..."
    ]);
    exit();
}
