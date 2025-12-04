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
        $categoria = $_POST['categoria'] ?? "sin categoria";
      
        try {
            $sql = "INSERT INTO codigos (codigo, estado) VALUES (:codigo, 1)";
            $insert = $mysql->getConexion()->prepare($sql);
            $insert->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $insert->execute();

            echo json_encode([
                "success" => true,
                "sala" => $codigo,
                "jugadores" => $cantidadJugadores,
                "modo" => $modoJuego,
                "categoria" => $categoria
            ]);
        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error al crear sala: " . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Faltan datos obligatorios."
        ]);
    }
}
