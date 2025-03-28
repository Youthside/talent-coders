<?php
header("Content-Type: application/json");
require_once "../../config/db.php";

try {
    $stmt = $conn->prepare("SELECT * FROM events ORDER BY date ASC ");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data"=> $events
    ]);
}
catch (PDOException $e) {
    echo json_encode([
        
        "success"=> false,
        "message"=> "Veritabanı Hatası",
        "error" => $e->getMessage()
    ]);

}

?>