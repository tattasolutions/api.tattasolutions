<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class RateOrder extends Model{
  const TABLE  =  "gb_rateOrder";
  
  public static function getById($id) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE ID='" . $id . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function getByInfo($idCustomer, $idUser, $idOrder) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE idCustomer='" . $idCustomer . "' and idUser='" . $idUser . "' and idOrder='" . $idOrder . "' ";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function getByUserId($userId, $withMedia=false) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    if ($withMedia) {
      $query = "SELECT idUser, avg(rate) as avg_rate FROM " . self::TABLE . " WHERE idUser='" . $userId . "' group by idUser";
      self::printQuery($query);
      $data = $mysql->fetch_single($query);
    } else {
      $query = "SELECT * FROM " . self::TABLE . " WHERE idUser='" . $userId . "'";
      self::printQuery($query);
      $data = $mysql->fetch_array($query);
    }
    $mysql->disconect();
    return $data;
  }
  
  public static function getByCustomerId($customerId, $withMedia=false) {
    $mysql = new MysqlDb();
    $mysql->conect();
  
    if ($withMedia) {
      $query = "SELECT idCustomer, avg(rate) as avg_rate FROM " . self::TABLE . " WHERE idCustomer='" . $customerId . "' group by idCustomer";
      self::printQuery($query);
      $data = $mysql->fetch_single($query);
    } else {
      $query = "SELECT * FROM " . self::TABLE . " WHERE idCustomer='" . $customerId . "'";
      self::printQuery($query);
      $data = $mysql->fetch_array($query);
    }
    
    $mysql->disconect();
    return $data;
  }
  
  public static function insert($idCustomer, $idUser, $idOrder, $rate) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query =
      "INSERT INTO " . self::TABLE .
      "(idCustomer, idUser, idOrder, rate) " .
      "VALUES " .
      "('" . $idCustomer . "','" . $idUser . "','" . $idOrder . "','" . $rate . "')";
    self::printQuery($query);
    $result = $mysql->query($query);
    if ($result) {
      $result = self::getByInfo($idCustomer, $idUser, $idOrder);
    }
    
    $mysql->disconect();
    return $result;
  }
}
?>