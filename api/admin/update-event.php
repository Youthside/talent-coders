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
$name = $data["name"] ?? null;
$detail = $data["detail"] ?? null;
$date = $data["date"] ?? null;
$type = $data["type"] ?? null;
$status = $data["status"] ?? "active";

if (!$name || !$detail || !$date || !$type) {
    echo json_encode([
        "success" => false,
        "message" => "Tüm alanlar zorunludur"
    ]);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE events SET name = :name, detail = :detail, date = :date, type = :type, status = :status WHERE id = :id");
    $success = $stmt->execute([
        ":id" => $id,
        ":name" => $name,
        ":detail" => $detail,
        ":date" => $date,
        ":type" => $type,
        ":status" => $status
    ]);

    if ($success) {
        echo json_encode([
            "success" => true,
            "message" => "Güncelleme başarılı"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Güncelleme başarısız"
        ]);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Veritabanı hatası",
        "error" => $e->getMessage()
    ]);
}
