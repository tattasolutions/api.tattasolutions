<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class ProductCategory extends Model{
  
  const TABLE  = PREFIX_TABLE . "term_relationships";
  
  public static function getByCategory($categoryId) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE term_taxonomy_id= $categoryId";
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $mysql->disconect();
    return $data;
  }
  
  public static function getByProduct($productId) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE object_id = $productId";
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $mysql->disconect();
    return $data;
  }
}