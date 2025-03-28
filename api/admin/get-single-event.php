<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

if (!isset($_GET["id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Etkinlik Id Gonderilmedi"
    ]);
    exit;
}

$id = $_GET["id"];

try {
    $stmt = $conn ->prepare("SELECT * FROM events WHERE id= :id");
    $stmt->execute([":id"=> $id]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if($event){
        echo json_encode([
            "success" => true,
            "data"=> $event
        ]) ;
    }
    else {
        echo json_encode([
            "success"=> false,
            "message"=> "Etkinlik Bulunamadı"
        ]);
    }
}catch(PDOException $e) {
    echo json_encode([
        "success"=> false,
        "message" => "Veritabanı Hatası",
        "error"=> $e->getMessage()
    ]);
}
?>