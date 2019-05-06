<?php

// Скрипт обрабатывает AJAX запросы на регистрацию от клиента.
// Возвращает в ответ JSON объект с данными.

$data = json_decode(file_get_contents("php://input"), true);
$answer = array("status" => false, "message" => "");

require_once("db.php");
require_once("cryptor.php");


if (!isset($data["login"]) || !isset($data["password"]) || !isset($data["repassword"]) ||
    !isset($data["email"]) || !isset($data["name"]))
  $answer["message"] = "Переданы не все поля! Обновите страницу!";

elseif (empty($data["login"]) || empty($data["password"]) || empty($data["repassword"]) ||
        empty($data["email"]) || empty($data["name"]))
  $answer["message"] = "заполнены не все поля!";

elseif ($data["password"] != $data["repassword"])
  $answer["message"] = "пароли не совпадают!";

elseif (strlen($data["login"]) < 3 && strlen($data["login"]) > 32)
  $answer["message"] = "логин должен иметь длину от 3 до 32-х символов!";

elseif (strlen($data["password"]) < 4 && strlen($data["password"]) > 32)
  $answer["message"] = "пароль должен иметь длину в диапазоне от 4 до 32 символов!";

if (strlen($answer["message"]) == 0) {

  $xmlDB = new XMLDB();

  $user_info = array(
    "login" => $data["login"],
    "password" => Cryptor::encryptText($data["password"]),
    "email" => $data["email"],
    "name" => $data["name"],
  );
  $answer["status"] = $xmlDB->insert("users", $user_info);
  if (!$answer["status"]) $answer["message"] = "Ошибка при добавлении новой записи в БД!";
}


echo json_encode($answer);

 ?>
