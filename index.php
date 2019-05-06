<?php

// Этот скрипт будет отвечать за показ страницы при обращении к ней любым путем, кроме AJAX

require_once("db.php");

$xmlDB = new XMLDB();

//var_dump($xmlDB->select("users", array("login", "name"), array("password"=>"iva")));
//$xmlDB->update("users", array("login"=>"updated"), array("login"=>"root"));
//$xmlDB->delete("users", array("password" => "root"));
//$xmlDB->insert("users", array("login"=>"killer", "password"=>"007"));

 ?>
