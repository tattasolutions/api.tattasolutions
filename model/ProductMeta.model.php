<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class ProductMeta extends Model{
  
  const TABLE  = PREFIX_TABLE . "postmeta";
  
  public static function getProductMetaByProductId($idProduct) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE post_id='" . $idProduct. "'";
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $dataParse = [];
    foreach ($data as $key => $value) {
      try{
        $data[$key]['meta_value'] = unserialize($data[$key]['meta_value']);
      }catch(Exception $e) {}
      
      $dataParse[$value['meta_key']] = $data[$key];
    }
    $mysql->disconect();
    return $dataParse;
  }
  
}