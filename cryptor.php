<?php

class Cryptor {

  public static function encryptText($text) {
    return self::getHash($text, substr(uniqid(), 0, 10));
  }

  public static function getHash($text, $salt) {
    return $salt . md5($salt . $text);
  }

  public static function confirmPassword($pass, $hash) {
    $salt = substr($hash, 0, 10);
    if (self::getHash($pass, $salt) == $hash) return true;

    return false;
  }

}

 ?>
