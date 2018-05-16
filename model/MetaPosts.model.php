<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class MetaPosts extends Model{
  
  const TABLE  = PREFIX_TABLE . "postmeta";
  
  public static function getMetaProductByProductId($idProduct) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE post_id='" . $idProduct. "'";
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $mysql->disconect();
    return $data;
  }
  
}