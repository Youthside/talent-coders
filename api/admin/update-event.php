<?php
header("Content-Type: application/json");
require_once "../../config/db.php";


$id = $_POST["id"] ?? null;
$name = $_POST["name"] ?? null;
$detail = $_POST["detail"] ?? null;
$date = $_POST["date"] ?? null;
$type = $_POST["type"] ?? null;
$status = $_POST["status"] ?? "active";

if (!$id || !$name || !$detail || !$date || !$type) {
    echo json_encode([
        "success" => false,
        "message" => "Lütfen tüm zorunlu alanları doldurunuz."
    ]);
    exit;
}

try {

    $stmt = $conn->prepare("SELECT image FROM events WHERE id = :id");
    $stmt->execute([":id" => $id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo json_encode([
            "success" => false,
            "message" => "Etkinlik bulunamadı."
        ]);
        exit;
    }

    $oldImage = $event["image"];
    $newImagePath = $oldImage;

  
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
        $targetDir = "../../uploads/";
        $fileName = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $targetPath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            $newImagePath = "uploads/" . $fileName;

           
            if (!empty($oldImage) && file_exists("../../" . $oldImage)) {
                unlink("../../" . $oldImage);
            }
        }
    }

  
    $stmt = $conn->prepare("UPDATE events SET name = :name, detail = :detail, date = :date, type = :type, status = :status, image = :image WHERE id = :id");
    $success = $stmt->execute([
        ":id" => $id,
        ":name" => $name,
        ":detail" => $detail,
        ":date" => $date,
        ":type" => $type,
        ":status" => $status,
        ":image" => $newImagePath
    ]);

    if ($success) {
        echo json_encode([
            "success" => true,
            "message" => "Etkinlik başarıyla güncellendi."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Güncelleme işlemi başarısız."
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
