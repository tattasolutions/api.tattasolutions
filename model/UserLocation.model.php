<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class UserLocation extends Model{
  const TABLE  =  "gb_userLocation";
  
  public static function getById($id) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE ID='" . $id . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function getByUserId($userId) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE idUser='" . $userId . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function getByPosition($lonStart, $lonEnd, $latStart, $latEnd) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE lon>=" . $lonStart. " and lon<=" . $lonEnd . " and lat>=" . $latStart . " and lat<=" . $latEnd;
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $mysql->disconect();
    return $data;
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