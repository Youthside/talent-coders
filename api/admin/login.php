<?php

header("Content-Type: application/json");

require_once "../../config/db.php";


$data = json_decode(file_get_contents("php://input"), true);


if (!isset($data["username"]) || !isset($data["password"])) {
    echo json_encode([
        "success" => false,
        "message" => "Kullanıcı adı veya şifre eksik"
    ]);
    exit;
}

$username = $data["username"];
$password = $data["password"];


$stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
$stmt->execute([$username]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);


if ($admin && $admin["password"] === $password) {
    echo json_encode([
        "success" => true,
        "message" => "Giriş başarılı",
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Kullanıcı adı veya şifre hatalı"
    ]);
}
?>
