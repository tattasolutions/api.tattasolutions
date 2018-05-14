<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class Profile extends Model{
  
  const TABLE  = PREFIX_TABLE . "bp_xprofile_data";
  
  public static function getUserProfile($userId) {
    $mysql = new MysqlDb();
    $mysql->conect();
  
    $query = "SELECT * FROM " . self::TABLE . " WHERE user_id=" . $userId;
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function getUserProfileByKey($userId, $key) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE user_id=" . $userId . "and field_id=" . $key;
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function setUserProfileKey($userId, $key, $value) {
    $mysql = new MysqlDb();
    $mysql->conect();
  
    $query = "INSERT INTO " . self::TABLE .
      "(field_id, user_id, value, last_updated) " .
      "VALUES ".
      "(" . $key . ", " . $userId . ", " . $value . ", CURDATE())";
    self::printQuery($query);
    $result = $mysql->query($query);
    if ($result) {
      $result = self::geByUserIdKey($userId, $key);
    }
  
    $mysql->disconect();
    return $result;
  }
}