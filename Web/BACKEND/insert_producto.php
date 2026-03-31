<?php
header('Content-Type: application/json');
require_once "conexion.php";

$nombre  = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$precio  = isset($_POST['precio']) ? trim($_POST['precio']) : '';
$detalle = isset($_POST['detalle']) ? trim($_POST['detalle']) : '';

if ($nombre === '' || $precio === '' || $detalle === '') {
    echo json_encode([
        "success" => false,
        "message" => "Todos los campos son obligatorios"
    ]);
    exit;
}

if (!is_numeric($precio)) {
    echo json_encode([
        "success" => false,
        "message" => "El precio debe ser numérico"
    ]);
    exit;
}

$idProducto = round(microtime(true) * 1000) + rand(10, 99);

$sql = "INSERT INTO PRODUCTO (idProducto, nombre, detalle, precio) VALUES (?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Error al preparar la consulta"
    ]);
    exit;
}

$stmt->bind_param("issd", $idProducto, $nombre, $detalle, $precio);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Producto registrado correctamente"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "No se pudo registrar el producto: " . $stmt->error
    ]);
}

$stmt->close();
$conexion->close();
?>