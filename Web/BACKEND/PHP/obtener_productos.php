<?php
header('Content-Type: application/json');
require_once "conexion.php";

$sql = "SELECT idProducto, nombre, detalle, precio FROM PRODUCTO ORDER BY idProducto DESC";
$resultado = $conexion->query($sql);

$productos = [];

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $productos[] = [
            "idProducto" => $fila["idProducto"],
            "nombre"     => $fila["nombre"],
            "detalle"    => $fila["detalle"],
            "precio"     => $fila["precio"]
        ];
    }
}

echo json_encode($productos, JSON_UNESCAPED_UNICODE);

$conexion->close();
?>