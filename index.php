<?php

// Этот скрипт будет отвечать за показ страницы при обращении к ней любым путем, кроме AJAX

require_once("db.php");
require_once("auth.class.php");

$xmlDB = new XMLDB();

session_start();
$user_name = Auth::createUser();
var_dump($user_name);

include "view/index.html";

 ?>
