<?php

$dbname = "ecommerce";
$username = "root";
$db_host = "localhost";
$password = "";
$sql = "mysql:host=$db_host;dbname=$dbname";
try {
    $conn = new PDO($sql, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
