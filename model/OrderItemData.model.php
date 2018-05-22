<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class OrderItemData extends Model{
  
  const TABLE  = PREFIX_TABLE . "woocommerce_order_itemmeta";
  
  public static function getById($id) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE ID='" . $id . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function insert($orderItemId, $key, $value) {
    $mysql = new MysqlDb();
    $mysql->conect();
  
    
    $query = "INSERT INTO ". self::TABLE . " " .
      "(order_item_id, meta_key, meta_value) ".
      "VALUES ".
      "($orderItemId, '$key', '$value')";
    self::printQuery($query);
    $data = $mysql->query($query);
    $mysql->disconect();
    return $data;
  }
}