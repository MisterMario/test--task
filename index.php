<?php

// Этот скрипт будет отвечать за показ страницы при обращении к ней любым путем, кроме AJAX

require_once("db.php");
require_once("auth.class.php");

session_start();
$user_name = Auth::createUser();

$registration_class = "hidden";
$authorization_class = "hidden";
$pr_class = "hidden";

if ($user_name == null) {
  $authorization_class = "";
} else {
  $pr_class = "";
}

include "view/index.html";

 ?>
