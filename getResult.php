<?php
header('Content-Type: application/json');

require_once 'config.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if(!isset($data['id']) || empty($data['id'])) {
  echo json_encode(["message" => "Data missing", "status" => 0, "code" => 400]); die();
}

$stmt = $conn->prepare("SELECT id as questionId,question,ansId FROM questions");
$stmt->execute();
$questions = $stmt->fetchAll();

$totalQuestions = count($questions);
$arrayResult = [];

$passed = 0;
$faild = 0;

foreach($questions as $question) {
  $ansId = $question['ansId'];

  $stmt = $conn->prepare("SELECT ansId as userAnsId FROM answers where userId = :userId AND qId = :qId");
  $stmt->execute([":userId" => $data['id'],":qId" =>$question['questionId']]);
  $userAns = $stmt->fetch();

  $userAnsId = $userAns['userAnsId'];

  if($userAnsId == $ansId) {
    $passed++;
  } else {
    $faild++;
  }

  $stmt = $conn->prepare("SELECT id as ansOptionId,ansOption FROM ansoptions where qId = ?");
  $stmt->execute([$question['questionId']]);
  $ansOptions = $stmt->fetchAll();
  
  $ansOptionWithResult = [];
  foreach($ansOptions as $ansOption) {
    if($ansOption['ansOptionId'] == $userAnsId) {
      $ansOption['userAns'] = true;
    } else {
      $ansOption['userAns'] = false;
    }

    if($ansOption['ansOptionId'] == $ansId) {
      $ansOption['ans'] = true;
    } else {
      $ansOption['ans'] = false;
    }

    array_push($ansOptionWithResult, $ansOption);
  }

  $question['ansOptions'] = $ansOptionWithResult;
  array_push($arrayResult, $question);
}

echo json_encode(["message" => "Success", "status" => 1, "code" => 200, "data" => $arrayResult, "totalQuestions" => $totalQuestions, "passed" => $passed, "faild" => $faild]);