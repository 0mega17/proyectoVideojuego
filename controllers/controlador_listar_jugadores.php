<?php
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

$sql = "SELECT * FROM aprendices ORDER BY id DESC";
$consulta = $mysql->getConexion()->prepare($sql);
$consulta->execute();

// Generar filas dinámicas
$html = "";
while ($fila = $consulta->fetch(PDO::FETCH_ASSOC)) {
    $html .= "
        <tr>
            <td>{$fila['nombre']}</td>
            <td>{$fila['ficha']}</td>
            <td>
                <button class='btn btn-danger btn-sm btnEliminar' data-id='{$fila['id']}'>
                    <i class='fa-solid fa-trash'></i> Eliminar
                </button>
            </td>
        </tr>
    ";
}

echo $html;
