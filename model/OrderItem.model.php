<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class OrderItem extends Model{
  
  const TABLE  = PREFIX_TABLE . "woocommerce_order_items";
  
  public static function getById($id) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE ID='" . $id . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function insert($productName, $orderId) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "INSERT INTO ". self::TABLE . " " .
      "(order_item_name, order_item_type, order_id) ".
      "VALUES ".
      "('$productName', 'line_item', $orderId)";
    self::printQuery($query);
    $data = $mysql->query($query);
    $mysql->disconect();
    return $data;
  }
}