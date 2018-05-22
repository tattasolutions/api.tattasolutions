<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class Order extends Model{
  
  const TABLE  = PREFIX_TABLE . "posts";
  
  public static function getById($id) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE ID='" . $id . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function getOrder($orderid) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE post_type='shop_order' and ID=" . $productid;
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function getListOrder() {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE post_type='shop_order'";
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function insert($userid) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $now = time();
    $date = date("Y-m-d H:i:s", $now);
    $gmdate = gmdate("Y-m-d H:i:s", $now);
    $title = "Order &ndash; " . $date;
    
    $query = "INSERT INTO ". self::TABLE . " " .
      "(post_author, post_date, post_date_gmt, post_title, post_modified, post_modified_gmt, post_parent, menu_order, post_type, post_content, post_excerpt, to_ping, pinged, post_content_filtered) ".
      "VALUES ".
      "($userid, '$date', '$gmdate', '$title', '$date', '$gmdate', 0, 0, 'shop_order', '', '', '', '', '')";
    self::printQuery($query);
    $data = $mysql->query($query);
    $mysql->disconect();
    return $data;
  }
}