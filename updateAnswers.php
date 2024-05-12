<?php
header('Content-Type: application/json');

require_once 'config.php';

$json = file_get_contents('php://input');
$datas = json_decode($json, true);

foreach($datas as $data) {
  if(isset($data['id']) && isset($data['ansId'])) {
    
    $sql = "UPDATE answers SET ansId=:ansId WHERE id =:id ";
    $stmt = $conn->prepare($sql);
    $dataSql = [
        ":ansId" => $data['ansId'],
        ":id" => $data['id']
    ];
    $stmt->execute($dataSql);

  } else {
    echo json_encode(["message" => "Data missing", "status" => 0, "code" => 400]); die();
  }
}

echo json_encode(["message" => "Success", "status" => 1, "code" => 200]);