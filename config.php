<?php
// Parámetros de conexión a la base de datos
$host = 'localhost';
$usuario = 'root'; // Usa el usuario real de tu base de datos
$contrasena = '';  // Usa tu contraseña
$baseDeDatos = 'siatecver3';

// Crear la conexión
$conexion = new mysqli($host, $usuario, $contrasena, $baseDeDatos);

// Verificar la conexión
if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

// Establecer codificación
$conexion->set_charset('utf8');
?>
