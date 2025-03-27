<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if(!isset($data["name"]) || !isset($data["detail"]) || !isset($data["date"]) || !isset($data["type"])){
    echo json_encode([
        "success" => false,
        "message"=> "Lütfen Eksik Alanları Doldurunuz"
    ]);
    exit;
}

$name = $data["name"];
$detail = $data["detail"];
$date = $data["date"];
$type = $data["type"];
$status = $data["status"] ?? "active";

try {
    $stmt = $conn->prepare("INSERT INTO events(name,detail,date,type,status) VALUES (:name,:detail,:date,:type,:status)");
    $success = $stmt->execute([
        ':name' => $name,
        ':detail' => $detail,
        ':date' => $date,
        ':type' => $type,
        ':status' => $status
    ]);

    if($success){
        echo json_encode([
            "success"=> true,
            "message"=> "Etkinlik başarıyla eklendi"
        ]);
    } else {
        echo json_encode([
            "success"=> false,
            "message"=> "Veritabanına ekleme işlemi başarısız"
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Veritabanı hatası",
        "error" => $e->getMessage()
    ]);
}
?>
