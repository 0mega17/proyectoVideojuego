<?php

// Conexion a la base de datos
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

try{

    $sql = "SELECT * FROM tipo_material";
    $consultaTipo = $mysql->getConexion()->prepare($sql);
    $consultaTipo->execute();

}catch(PDOException $e){
    echo json_encode([
        "success" => false,
        "message" => "Ocurrio un error en la consulta" . $e->getMessage()
    ]);
    exit();
}


$tipos = [];

while($fila = $consultaTipo->fetch(PDO::FETCH_ASSOC)){
    $tipos[] = $fila;
}

header("ContentType:application/json");
echo json_encode($tipos);

?>