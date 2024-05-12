<?php

header('Content-Type: application/json');

require_once 'config.php';

$stmt = $conn->prepare("SELECT id as questionId,question FROM questions");
$stmt->execute();
$result = $stmt->fetchAll();

$arrayResult = [];

foreach($result as $data) {
  $stmt = $conn->prepare("SELECT id as ansOptionId,ansOption FROM ansoptions where qId = ?");
  $stmt->execute([$data['questionId']]);
  $resultOne = $stmt->fetchAll();
  $data['ansOptions'] = $resultOne;
  array_push($arrayResult, $data);
}

echo json_encode(["message" => "Success", "status" => 1, "code" => 200, "data" => $arrayResult]);

