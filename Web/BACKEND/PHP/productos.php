<?php
include_once 'conexion.php';


$query = "SELECT * FROM PRODUCTO";
try {
    
    $stmt = $conn->prepare($query);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}




?>