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
    $stmt = $conn->prepare("DELETE FROM events WHERE id = :id");
    $result = $stmt->execute([":id" => $id]);

    if (!$result) {
        echo json_encode([
            "success" => false,
            "message" => "Veri silinemedi"
        ]);
    } else {
        echo json_encode([
            "success" => true,
            "message" => "Veri başarıyla silindi"
        ]);
    }

} catch(PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Veritabanı hatası",
        "error" => $e->getMessage()
    ]);
}
?>
