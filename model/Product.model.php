<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";
require_once "ProductMeta.model.php";


class Product extends Model{
  
  const TABLE  = PREFIX_TABLE . "posts";
  
  public static function getById($id) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE ID='" . $id . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    
    $data['metaData'] = self::getMetaData($data['ID']);
    
    $mysql->disconect();
    return $data;
  }
  
  public static function getListProduct() {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE post_type='product'";
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
  
    if($data) {
      foreach ($data as $key => $velue) {
        $data[$key]['metaData'] = self::getMetaData($velue['ID']);
      }
    }
    
    $mysql->disconect();
    return $data;
  }
  
  private static function getMetaData($id) {
    if ($id) {
      return ProductMeta::getProductMetaByProductId($id);
    }
    
    return [];
  }
}