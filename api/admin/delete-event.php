<?php
header("Content-Type: application/json");
require_once "../../config/db.php";


$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Etkinlik ID gönderilmedi"
    ]);
    exit;
}

$id = $data["id"];

try {

    $stmt = $conn->prepare("SELECT image FROM events WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo json_encode([
            "success" => false,
            "message" => "Etkinlik bulunamadı"
        ]);
        exit;
    }

    $imagePath = $event["image"];


    $stmt = $conn->prepare("DELETE FROM events WHERE id = :id");
    $success = $stmt->execute([":id" => $id]);

    if (!$success) {
        echo json_encode([
            "success" => false,
            "message" => "Veri silinemedi"
        ]);
        exit;
    }


    if (!empty($imagePath) && file_exists("../../" . $imagePath)) {
        unlink("../../" . $imagePath);
    }

    echo json_encode([
        "success" => true,
        "message" => "Etkinlik ve resmi başarıyla silindi"
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Veritabanı hatası",
        "error" => $e->getMessage()
    ]);
}
