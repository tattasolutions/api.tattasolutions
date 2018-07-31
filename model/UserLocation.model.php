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
	public static function getAll() {
		$mysql = new MysqlDb();
		$mysql->conect();
		
		$query = "SELECT * FROM " . self::TABLE;
		self::printQuery($query);
		$data = $mysql->fetch_array($query);
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
  
  public static function insert($userId, $lat, $lon) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query =
      "INSERT INTO " . self::TABLE .
      "(`idUser`, `lat`, `lon`) " .
      "VALUES " .
      "('" . $userId . "','" . $lat . "','" . $lon . "')";
    self::printQuery($query);
    $result = $mysql->query($query);
    if ($result) {
      $result = self::getByUserId($userId);
    }
    
    $mysql->disconect();
    return $result;
  }

  public static function update($userId, $lat, $lon) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query =
      "UPDATE " . self::TABLE . " SET " .
      "lat = '" . $lat . "', " .
      "lon = '" . $lon . "' " .
      "WHERE idUser = '" . $userId ."'";
    self::printQuery($query);
    
    $mysql->query($query);
    
    $result = self::getByUserId($userId);
    
    $mysql->disconect();
    return $result;
  }
}
?>