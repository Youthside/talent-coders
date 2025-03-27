<?php
$host = "localhost";
$db_name = "talentcoders_db";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die(json_encode([
        "success" => false,
        "message" => "Veritabanı bağlantı hatası",
        "error" => $e->getMessage()
    ]));
}
?>
