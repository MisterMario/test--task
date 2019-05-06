<?php

// Скрипт обработчик AJAX запроса на выход из личного кабинета

$data = json_decode(file_get_contents("php://input"), true);
$answer = array("status" => false, "message" => "");

require_once("db.php");
require_once("auth.class.php");


if (isset($data["logout"])) {

  session_start();
  $answer["status"] = Auth::sessionDestroy();
  if (!$answer["status"]) $answer["message"] = "Ошибка: не удается разорвать соединение!";

}


echo json_encode($answer);

 ?>
