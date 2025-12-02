<?php
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "db_gestion_notas";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error en la conexión con la base de datos: " . $e->getMessage();
    die();
}
?>