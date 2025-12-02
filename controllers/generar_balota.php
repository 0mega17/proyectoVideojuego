<?php


// Conexion a la base de datos
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$errores = [];
$composiciones = [];
$columnas = ["titulo", "autor", "frase"];

// Lee el stream de entrada sin procesar
$json_string = file_get_contents('php://input');
$arregloBalotas = json_decode($json_string, true);
$conteoFilas = 0;


try {
    $sql = "SELECT * FROM composiciones";
    $consultaComposiciones = $mysql->getConexion()->prepare($sql);
    $consultaComposiciones->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en composiciones..." . $e->getMessage();
}

try {
    $sql = "SELECT COUNT(*) as conteo FROM composiciones";
    $consultaConteo = $mysql->getConexion()->prepare($sql);
    $consultaConteo->execute();
    $conteoFilas = $consultaConteo->fetch(PDO::FETCH_ASSOC)["conteo"];
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en composiciones..." . $e->getMessage();
}

$limiteBaraja = $conteoFilas * count($columnas);    


while ($fila = $consultaComposiciones->fetch(PDO::FETCH_ASSOC)) {
    $composiciones[] = $fila;
}


function revolverBalotas($composiciones, $conteoFilas)
{
    $numRandom = mt_rand(1, $conteoFilas - 1);
    shuffle($composiciones);
    $balotaGeneral = $composiciones[$numRandom];
    return $balotaGeneral;
}

$balotaGeneral = revolverBalotas($composiciones, $conteoFilas);

$numCol = mt_rand(0, 2);
$balota = $balotaGeneral[$columnas[$numCol]];


if (count($arregloBalotas) > 0) {
    for ($i = 0; $i < count($arregloBalotas); $i++) {

        $balotasRepetidas = $arregloBalotas[$i]["balota"];
        $conteo = count($arregloBalotas);

        if($limiteBaraja <= count($arregloBalotas)){
            echo json_encode([
                "success" => true,
                "balota" => "Todas las balotas fueron generadas",
                "columna" => "Sin columna"
            ]);
            exit();
        }


        if ($arregloBalotas[$i]["balota"] == $balota) {
            $balotaGeneral = revolverBalotas($composiciones, $conteoFilas);
            $numCol = mt_rand(0, 2);
            $balota = $balotaGeneral[$columnas[$numCol]];
            $i = 0;
        }
    }
}

switch ($numCol) {
    case 0:
        $columna = "Titulo";
        break;
    case 1:
        $columna = "Autor";
        break;
    case 2:
        $columna = "Frase";
        break;
    default:
        $columna = "N/A";
}


echo json_encode([
    "success" => true,
    "balota" => $balota,
    "columna" => $columna
]);
