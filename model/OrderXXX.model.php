<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class Order extends Model{
  
  const TABLE  = PREFIX_TABLE . "woocommerce_order_items";
  
  public static function getProduct() {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE post_type='product'";
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $mysql->disconect();
    return $data;


"INSERT INTO ".
"u4y_woocommerce_order_items".
"(order_item_name, order_item_type, order_id) ".
"VALUES ".
"([value-1],[value-2],[value-3],[value-4])";
}