<?php
header('Content-Type: application/json; charset=utf-8');

$host = "localhost";
$dbname = "ecommerce";
$user = "root";
$pass = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión: " . $e->getMessage()
    ]);
    exit;
}

$accion = $_GET['accion'] ?? '';

if ($accion === 'listar') {
    try {
        $sql = "SELECT idProducto, nombre, detalle, precio
                FROM PRODUCTO
                ORDER BY idProducto DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $productos = $stmt->fetchAll();

        echo json_encode($productos, JSON_UNESCAPED_UNICODE);
        exit;

    } catch (PDOException $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error al obtener productos: " . $e->getMessage()
        ]);
        exit;
    }
}

echo json_encode([
    "success" => false,
    "message" => "Acción no válida"
]);
?>