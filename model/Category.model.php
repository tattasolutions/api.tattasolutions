<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";
require_once "CategoryMeta.model.php";


class Category extends Model{
  
  const TABLE  = PREFIX_TABLE . "terms";
  
  public static function getById($id) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE term_id='" . $id . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    
    $data['metaData'] = Self::getMetaData($data['term_id']);
    
    $mysql->disconect();
    return $data;
  }
  
  public static function getBySlug($slugCategory) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE slug='$slugCategory'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
  
    $data['metaData'] = Self::getMetaData($data['term_id']);
    
    $mysql->disconect();
    return $data;
  }
  
  private static function getMetaData($id) {
    if ($id) {
      return CategoryMeta::getById($id);
    }
    
    return [];
  }
}