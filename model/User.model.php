<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class User extends Model{
  const TABLE  = PREFIX_TABLE . "users";
  
  const USER_NOT_VALIDATE = 0;
  const USER_VALIDATE = 1;
  
  public static function getById($id) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE ID='" . $id . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function getUserByUsername($username) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE user_login='" . $username . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }

  public static function getUserByMail($mail) {
    $mysql = new MysqlDb();
    $mysql->conect();
  
    $query = "SELECT * FROM " . self::TABLE . " WHERE user_email='" . $mail . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
  
    $mysql->disconect();
    return $data;
  }
  
  public static function updatePassword($userId, $password) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "UPDATE " . self::TABLE . " SET user_pass = '" . $password . "' WHERE ID=" . $userId;
    self::printQuery($query);
    $result = $mysql->query($query);
    
    $mysql->disconect();
    return $result;
  }
  
  //$cf, $name, $surname, $address, $birthDate, $typeUser, $typeManifactur, $typePilot
  public static function insert($username, $mail, $password, $niceName, $displayName, $activationKey) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query =
      "INSERT INTO " . self::TABLE .
      "(`user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_activation_key`, `user_status`, `display_name`, `user_registered`) " .
      "VALUES " .
      "('" . $username . "','" . $password . "','" . $niceName . "','" . $mail . "','', '" . $activationKey . "','0', '" . $displayName . "', CURDATE())";
    self::printQuery($query);
    $result = $mysql->query($query);
    if ($result) {
      $result = self::getUserByUsername($username);
    }
    
    $mysql->disconect();
    return $result;
  }
}
?>