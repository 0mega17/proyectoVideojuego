<?php
// Clase para manejar la conexión a la base de datos con PDO
class MySQL {
// Variable para almacenar la conexión PDO
private $conexion;

// Método para establecer la conexión
public function conectar() {
$host = 'localhost';
$dbname = 'bingo_literario_2';
$usuario = 'root';
$contrasena = '';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
$this->conexion = new PDO($dsn, $usuario, $contrasena);
$this->conexion->setAttribute(PDO::ATTR_ERRMODE,
PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
die("Error de conexión: " . $e->getMessage());
}
}

// Método para cerrar la conexión
public function desconectar() {
$this->conexion = null;
}


// Método para obtener la conexión PDO (para consultas preparadas)
public function getConexion() {
return $this->conexion;
}
}
?>