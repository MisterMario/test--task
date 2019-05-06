<?php

// Скрипт будет отвечать за обработку AJAX запросов на регистрацию от клиента
// Скрипт возвращает в ответ JSON объект с данными

$data = json_decode(file_get_contents("php://input"), true);
$answer = false;



echo json_encode($answer);

 ?>
