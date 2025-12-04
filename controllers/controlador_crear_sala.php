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

          
        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error al crear sala: " . $e->getMessage()
            ]);
        }

        try {
            $sqlID = "SELECT MAX(id) as IDmaximo FROM codigos";
            $consultaID = $mysql->getConexion()->prepare($sqlID);
            $consultaID->execute();
            $IDmaximo = $consultaID->fetch(PDO::FETCH_ASSOC)["IDmaximo"];
        } catch (PDOException $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error al captura IDmaximo sala: " . $e->getMessage()
            ]);
        }

        try{
            $sqlEstado = "INSERT INTO estado_juego(estado, codigos_id) VALUES(:estado, :IDmaximo)";
            $insertEstado = $mysql->getConexion()->prepare($sqlEstado);
            $estado = "Activo";
            $insertEstado->bindParam("estado", $estado, PDO::PARAM_STR);
            $insertEstado->bindParam("IDmaximo", $IDmaximo, PDO::PARAM_INT);
            $insertEstado->execute();
        }catch(PDOException $e){
            echo json_encode([
                "success" => false,
                "message" => "Error al insertar el estado de la sala: " . $e->getMessage()
            ]);
        }


        echo json_encode([
            "success" => true,
            "ID" => $IDmaximo,
            "sala" => $codigo,
            "jugadores" => $cantidadJugadores,
            "modo" => $modoJuego,
            "categoria" => $categoria
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Faltan datos obligatorios."
        ]);
    }
}
