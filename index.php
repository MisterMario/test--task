<?php

// Этот скрипт будет отвечать за показ страницы при обращении к ней любым путем, кроме AJAX

require_once("db.php");

$xmlDB = new XMLDB();
//var_dump($xmlDB->select("users", array("password", "name"), array("login"=>"root"), 1));

include "view/index.html";

 ?>
