<?php
// Conexion a la base de datos
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Arreglos necesarios para revolver la baraja
$errores = [];
$composiciones = [];
$columnas = ["titulo", "autor", "frase"];

// Codigo
session_start();
$codigoSala = $_SESSION["codigoSala"];

// Captura del arreglo de las balotas que ya salieron
$arregloBalotas = json_decode($_POST["arregloBalotas"], true);
$categoria = intval($_POST["categoria"]);


// Consulta para traer todas las casillas de las tablas de los jugadores
try {
    $sql = "
SELECT DISTINCT c.contenido, comp.titulo, comp.autor, comp.frase, comp.tipo_material_id
FROM casillas_tablas c
JOIN tablas t ON t.id = c.tablas_id
LEFT JOIN composiciones comp 
    ON comp.titulo = c.contenido
    OR comp.autor = c.contenido
    OR comp.frase = c.contenido
WHERE t.codigos_codigo = :codigoSala
";

    $consulta = $mysql->getConexion()->prepare($sql);
    $consulta->bindParam(":codigoSala", $codigoSala);
    $consulta->execute();

    $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $errores[] = "Ocurrio un error en composiciones..." . $e->getMessage();
}


$balotasDisponibles = [];

foreach ($resultados as $fila) {

    $columna = null;
    $tipoObra = null;

    if ($fila["contenido"] === $fila["titulo"]) {
        $columna = "titulo";
    } elseif ($fila["contenido"] === $fila["autor"]) {
        $columna = "autor";
    } elseif ($fila["contenido"] === $fila["frase"]) {
        $columna = "frase";
    }

    if ($fila["tipo_material_id"] == 1) {
        $tipoObra = "Libro";
    } elseif ($fila["tipo_material_id"] == 2) {
        $tipoObra = "Poema";
    }

    $balotasDisponibles[] = [
        "texto" => $fila["contenido"],
        "columna" => $columna,
        "tipo_obra" => $tipoObra
    ];
}

$columna = "";
$tipoObra = "";

function determinarColumnaLibro($mysql, $comp, $columnaBD)
{
    // Titulo
    try {
        $tipoMaterial = 1;
        $sql = "SELECT COUNT(*) as conteo FROM composiciones WHERE $columnaBD = :comp AND tipo_material_id = :tipoID ";
        $consultaLibro = $mysql->getConexion()->prepare($sql);
        $consultaLibro->bindParam("comp", $comp["contenido"]);
        $consultaLibro->bindParam("tipoID", $tipoMaterial);
        $consultaLibro->execute();
        $conteo = $consultaLibro->fetch(PDO::FETCH_ASSOC)["conteo"];
        if ($conteo > 0) {
            $info = [
                "columna" => $columnaBD,
                "tipoObra" => "Libro"
            ];
        } else {
            $info = [];
        }

        return $info;
    } catch (PDOException $e) {
        $errores[] = "Ocurrio un error en composiciones..." . $e->getMessage();
    }
}

function determinarColumnaPoema($mysql, $comp, $columnaBD)
{
    // Titulo
    try {
        $tipoMaterial = 2;
        $sql = "SELECT COUNT(*) as conteo FROM composiciones WHERE $columnaBD = :comp AND tipo_material_id = :tipoID ";
        $consultaPoema = $mysql->getConexion()->prepare($sql);
        $consultaPoema->bindParam("comp", $comp["contenido"]);
        $consultaPoema->bindParam("tipoID", $tipoMaterial);
        $consultaPoema->execute();
        $conteo = $consultaPoema->fetch(PDO::FETCH_ASSOC)["conteo"];
        if ($conteo > 0) {
            $info = [
                "columna" => $columnaBD,
                "tipoObra" => "Poema"
            ];
        } else {
            $info = [];
        }

        return $info;
    } catch (PDOException $e) {
        $errores[] = "Ocurrio un error en composiciones..." . $e->getMessage();
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
        "columna" => ucfirst($balotaFinal["columna"]),
        "tipo_obra" => $balotaFinal["tipo_obra"]
    ]);
}
