<?php

// Этот скрипт будет отвечать за показ страницы при обращении к ней любым путем, кроме AJAX

require_once("db.php");

$xmlDB = new XMLDB();

include "view/index.html";

 ?>
