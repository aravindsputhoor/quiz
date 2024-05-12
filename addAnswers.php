<?php
header('Content-Type: application/json');

require_once 'config.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if(!isset($data['userId']) || !isset($data['qId']) || !isset($data['ansId'])) {
  echo json_encode(["message" => "Data missing", "status" => 0, "code" => 400]); die();
}

$insertData = [
  ":userId" => $data['userId'],
  ":qId" => $data['qId'],
  ":ansId" => $data['ansId'],
];
// Prepare SQL query
$sql = "INSERT INTO answers (userId, qId, ansId) VALUES (:userId, :qId, :ansId)";
$stmt = $conn->prepare($sql);

// Execute the query
if ($stmt->execute($insertData)) {
  echo json_encode(["message" => "Success", "id" => $conn->lastInsertId(), "status" => 1, "code" => 200]);
} else {
  echo json_encode(["message" => "Error", "status" => 0, "code" => 400]);
}