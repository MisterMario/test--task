<?php

// Этот скрипт будет отвечать за показ страницы при обращении к ней любым путем, кроме AJAX

require_once("db.php");

$xmlDB = new XMLDB();
var_dump($xml_file_content = $xmlDB->select("users", array("login", "password", "email", "name")));
;
 ?>
