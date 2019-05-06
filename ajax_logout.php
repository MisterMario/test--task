<?php

// Скрипт обработчик AJAX запроса на выход из личного кабинета

$data = json_decode(file_get_contents("php://input"), true);
$answer = array("status" => false, "message" => "");

require_once("db.php");


if (isset($data["logout"])) {

  

}


echo json_encode($answer);

 ?>
