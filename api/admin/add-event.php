<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

$name = $_POST["name"] ?? null;
$detail = $_POST["detail"] ?? null;
$date = $_POST["date"] ?? null;
$type = $_POST["type"] ?? null;
$status = $_POST["status"] ?? "active";

if(!$name || !$detail || !$date || !$type){
    echo json_encode([
        "success" => false,
        "message" => "Lütfen eksik alanları doldurunuz"
    ]);
    exit;
}

$imagePath = null;

if(isset($_FILES["image"]) && $_FILES["image"]["error"] === 0) {
    $targetDir = "../../uploads/";
    $fileName =uniqid()."_".basename($_FILES["image"]["name"]);
    $targetPath = $targetDir.$fileName;

    if(move_uploaded_file($_FILES["image"]["tmp_name"],$targetPath)){
        $imagePath = "uploads/" . $fileName;
    }
}

try {
    $stmt = $conn->prepare("INSERT INTO events(name,detail,date,type,status,image)
    VALUES (:name,:detail,:date,:type,:status,:image)");
    $success = $stmt->execute([
        ":name"=> $name,
        ":detail"=> $detail,
        ":date"=> $date,
        ":type"=> $type,
        ":status"=> $status,
        ":image" => $imagePath,
    ]);

    if(!$success){
        echo json_encode([
            "success"=> false,
            "message"=> "veritabanına ekleme başarısız"
        ]);
        exit;
    }
    else{
        echo json_encode([
            "success"=> true,
            "message"=> "veritabanına ekleme başarılı"
        ]);
    }
}catch(PDOException $e){
    echo json_encode([
        "success"=> false,
        "message"=> "veritabanı bağlantı hatası",
        "error"=> $e->getMessage()
    ]);
}
?>