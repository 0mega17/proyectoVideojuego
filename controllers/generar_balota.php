<?php
// Conexion a la base de datos
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Arreglos necesarios para revolver la baraja
$errores = [];
$composiciones = [];
$columnas = ["titulo", "autor", "frase"];

// Captura del arreglo de las balotas que ya salieron
$arregloBalotas = json_decode($_POST["arregloBalotas"], true);
$categoria = intval($_POST["categoria"]);


// Consulta para traer todas las composiciones
try {
    if ($categoria == 0) {
        $sql = "SELECT composiciones.id, composiciones.titulo, composiciones.autor, composiciones.frase, tipo_material.nombre as tipo_obra FROM composiciones JOIN tipo_material ON composiciones.tipo_material_id = tipo_material.id";
    } else {
        $sql = "SELECT composiciones.id, composiciones.titulo, composiciones.autor, composiciones.frase, tipo_material.nombre as tipo_obra FROM composiciones JOIN tipo_material ON composiciones.tipo_material_id = tipo_material.id JOIN categorias_has_composiciones ON categorias_has_composiciones.composiciones_id = composiciones.id WHERE categorias_has_composiciones.categorias_id = $categoria";
    }

    $consultaComposiciones = $mysql->getConexion()->prepare($sql);
    $consultaComposiciones->execute();
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en composiciones..." . $e->getMessage();
}


// Llenar el arreglo con todas las obras literarias
while ($fila = $consultaComposiciones->fetch(PDO::FETCH_ASSOC)) {
    $composiciones[] = $fila;
}

$balotasDisponibles = [];

// Llenar el arreglo con las balotas disponibles
foreach ($composiciones as $comp) {
    foreach ($columnas as $col) {
        if (!empty($comp[$col])) {
            $balotasDisponibles[] = [
                "texto" => $comp[$col],
                "columna" => ucfirst($col),
                "tipo_obra" => $comp["tipo_obra"]
            ];
        }
    }
}

// Extraer el contenido de las balotas usadas
$balotasUsadas = array_column($arregloBalotas, 'balota');

// Filtrar con un array method las balotas que no han salido
// 1er parametro el arreglo general
// 2do parameto funcion callback que retorna los elementos que no 
// coinciden entre el texto de balotas disponibles y balotas usadas 
$balotasDisponibles = array_filter(
    $balotasDisponibles,
    function ($b) use ($balotasUsadas) {
        return !in_array($b["texto"], $balotasUsadas);
    }
);

// Determinar si no han terminado todas las balotas
if (count($balotasDisponibles) === 0) {
    echo json_encode([
        "success" => false,
        "balota" => "Todas las balotas fueron generadas",
        "columna" => "Sin columna"
    ]);
    exit();
}

// Extraer un elemento random del arreglo de las balotas ya filtrado
$balotaFinal = $balotasDisponibles[array_rand($balotasDisponibles)];


// Enviar la informacion al JS
if (count($errores) === 0) {
    echo json_encode([
        "success" => true,
        "balota" => $balotaFinal["texto"],
        "columna" => $balotaFinal["columna"],
        "tipo_obra" => $balotaFinal["tipo_obra"]
    ]);
}
