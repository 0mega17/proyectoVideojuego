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
// Captura del arreglo de las balotas que ya salieron
$arregloBalotas = json_decode($json_string, true);
$conteoFilas = 0;

// Consulta para traer todas las composiciones
try {
    $sql = "SELECT * FROM composiciones";
    $consultaComposiciones = $mysql->getConexion()->prepare($sql);
    $consultaComposiciones->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en composiciones..." . $e->getMessage();
}

// Consulta para contar el numero de composiciones
try {
    $sql = "SELECT COUNT(*) as conteo FROM composiciones";
    $consultaConteo = $mysql->getConexion()->prepare($sql);
    $consultaConteo->execute();
    $conteoFilas = $consultaConteo->fetch(PDO::FETCH_ASSOC)["conteo"];
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en composiciones..." . $e->getMessage();
}

// Establecer el limite de posibilidades para revolver
$limiteBaraja = $conteoFilas * count($columnas);


// Llenar el arreglo con todas las obras literarias
while ($fila = $consultaComposiciones->fetch(PDO::FETCH_ASSOC)) {
    $composiciones[] = $fila;
}

// Funcion para determinar que arreglo seleccionar de manera aleatoria
function revolverBalotas($composiciones, $conteoFilas)
{
    // Numero random entre los valores posibles del arreglo
    $numRandom = mt_rand(1, $conteoFilas - 1);
    // Revolver el arreglo entre posiciones
    shuffle($composiciones);
    // Determinar del arreglo que elemento selccionar  con el numero random
    $balotaGeneral = $composiciones[$numRandom];
    return $balotaGeneral;
}

// Revoler las balotas con la funcion
$balotaGeneral = revolverBalotas($composiciones, $conteoFilas);

// Numero random para las columnas a escoger
$numCol = mt_rand(0, 2);
// Seleccionar de la balota seleccionada una columna con el numero random
$balota = $balotaGeneral[$columnas[$numCol]];


if (count($arregloBalotas) > 0) {
    for ($i = 0; $i < count($arregloBalotas); $i++) {
        // Elemento del array de las balotas que ya salieron
        $balotasRepetidas = $arregloBalotas[$i]["balota"];
        $conteo = count($arregloBalotas);

        // Decision para determninar si ya se llego al limite
        if ($limiteBaraja <= count($arregloBalotas)) {
            echo json_encode([
                "success" => true,
                "balota" => "Todas las balotas fueron generadas",
                "columna" => "Sin columna"
            ]);
            exit();
        }


        // Determinar si el elemento ya salio
        if ($balotasRepetidas == $balota) {
            // Volver a revolver y seleccionar otro elemento
            $balotaGeneral = revolverBalotas($composiciones, $conteoFilas);
            $numCol = mt_rand(0, 2);
            $balota = $balotaGeneral[$columnas[$numCol]];
            // Reiniciar el ciclo otra vez
            $i = 0;
        }
    }
}

// Determinar que columna saliò
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


// Enviar la informacion al JS
echo json_encode([
    "success" => true,
    "balota" => $balota,
    "columna" => $columna
]);
