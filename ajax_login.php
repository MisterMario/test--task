<?php

// Обработчик AJAX запросов на авторизацию от клиента
// Возвращает в ответ JSON объект с данными.

$data = json_decode(file_get_contents("php://input"), true);
$answer = array("status" => false, "message" => "");

require_once("db.php");
require_once("cryptor.php");


if (!isset($data["login"]) || !isset($data["password"]))
  $answer["message"] = "Переданы не все поля! Обновите страницу!";

elseif (empty($data["login"]) || empty($data["password"]))
  $answer["message"] = "не все поля заполнены!";

if (strlen($answer["message"]) == 0) {

  $xmlDB = new XMLDB();
  $selection = $xmlDB->select("users", array("password", "name"), array("login"=>$data["login"]));
  if (count($selection) > 0) {
    
    if (Cryptor::confirmPasswords($data["password"], $selection[0]["password"])) {

      // создание кукисов и сессии
      $answer["status"] = true;
      $answer["message"] = $selection[0]["name"];

    } else $answer["message"] = "неверный пароль!";

  } else $answer["message"] = "такого пользователя не существует!";

}


echo json_encode($answer);

?>
