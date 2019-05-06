<?php

/*
  Класс для работы с куками и сессией взят из моего проекта "Личный кабинет на AJAX"
  (c) Mr_Mario
*/

class Auth {

  /* Функция для генерации случайной строки */
  public static function TmpGenerate($tmp_length = 32){
  	$allchars = "abcdefghijklmnopqrstuvwxyz0123456789";
  	$output = "";
    mt_srand( (double) microtime() * 1000000 );
  	for($i = 0; $i < $tmp_length; $i++){
  	   $output .= $allchars{ mt_rand(0, strlen($allchars)-1) };
  	}
  	return $output;
  }

  /* Создает сессию и куки для пользователя */
  public static function sessionCreate($data) {
    if (!isset($_SESSION["user_name"]) && !isset($_COOKIE["bw-v1-login"])) { // Если сессия и куки не созданы
      $_SESSION["user_name"] = $data["name"];

      $tmp = self::TmpGenerate();
      $xmlDB = new xmlDB();
      if (!$xmlDB->update("users", array("tmp"=>$tmp, "session_id"=>session_id()),
          array("login"=>$data["login"]))) return false;

      setcookie("pr-v1-login", $data["login"], time()+3600*24*30, "/");
      setcookie("pr-v1-tmp", $tmp, time()+3600*24*30, "/");

      return true;
    }
    return false;
  }

  /* Уничтожает сессию и куки пользователя */
  public static function sessionDestroy() {
    session_unset();
    session_destroy();
    if (isset($_COOKIE["pr-v1-login"])) {
      setcookie("pr-v1-login", "", time()-3600, "/");
      setcookie("pr-v1-tmp", "", time()-3600, "/");

      $xmlDB = new xmlDB();
      if ($xmlDB->update("users", array("tmp"=>"", "session_id"=>""), array("login"=>$data["login"]))) return true;
    }
    return false;
  }

  public static function createUser() {

    if (isset($_SESSION["user_name"])) { // Получение данных о пользователе из сессии
      return $_SESSION["user_name"];
    } elseif (isset($_COOKIE["pr-v1-login"])) { // Получение даннных о пользователе из кук

      $xmlDB = new xmlDB();
      $selection = $xmlDB->select("users", array("name"),
                                  array("login"=>$_COOKIE["pr-v1-login"], "tmp"=>$_COOKIE["pr-v1-tmp"]), 1);

      if (count($selection) != 0) {
        $_SESSION["user_name"] = $selection[0]["name"];
        $xmlDB->update("users", array("session_id"=>session_id()), array("login"=>$_COOKIE["pr-v1-login"]));

        return $_SESSION["user_name"];

      } else { // Если информация из кук устарела или ложная
        setcookie("pr-v1-login", "", time()-3600, "/");
        setcookie("pr-v1-tmp", "", time()-3600, "/");
      }
    }
    return null;
  }
}

?>
