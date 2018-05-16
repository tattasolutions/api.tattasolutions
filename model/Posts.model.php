<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class Posts extends Model{
  
  const TABLE  = PREFIX_TABLE . "posts";
  
  public static function getProduct() {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE post_type='product'";
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $mysql->disconect();
    return $data;
  }
  
}