<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class Token extends Model{
  private const TABLE  = PREFIX_TABLE . "token";
  
  public static function getTokenByUserId($userId) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE user_id=" . $userId;
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    
    $mysql->disconect();
    return $data;
  }
  
  public static function deleteTokenByUserId($userId) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "DELETE FROM " . self::TABLE . " WHERE user_id=" . $userId;
    self::printQuery($query);
    $data = $mysql->query($query);
    
    $mysql->disconect();
    return $data;
  }

  public static function setTokenByUserId($userId, $token, $expire) {
    $mysql = new MysqlDb();
    $mysql->conect();
  
    $query = "INSERT INTO " . self::TABLE . " (user_id, token, expire) VALUES (" . $userId . ", '" . $token . "', FROM_UNIXTIME(" . $expire . "))";
    self::printQuery($query);
    $result = $mysql->query($query);
  
    $mysql->disconect();
    return $result;
  }
}
?>