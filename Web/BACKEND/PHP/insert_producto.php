<?php
header('Content-Type: application/json; charset=utf-8');

$host = "localhost";
$dbname = "ecommerce";
$user = "root";
$pass = "";

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

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $idProducto = round(microtime(true) * 1000) + rand(10, 99);

    $sql = "INSERT INTO PRODUCTO (idProducto, nombre, detalle, precio)
            VALUES (:idProducto, :nombre, :detalle, :precio)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':idProducto', $idProducto, PDO::PARAM_INT);
    $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindValue(':detalle', $detalle, PDO::PARAM_STR);
    $stmt->bindValue(':precio', $precio);

    $stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "Producto registrado correctamente"
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error al registrar el producto: " . $e->getMessage()
    ]);
}
?>