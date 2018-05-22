<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class PageMeta extends Model{
  
  const TABLE  = PREFIX_TABLE . "postmeta";
  
  public static function getPageMetaByPageId($idPage) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE post_id='" . $idPage. "'";
    self::printQuery($query);
    $data = $mysql->fetch_array($query);
    $dataParse = [];
    foreach ($data as $key => $value) {
      try{
        $data[$key]['meta_value'] = unserialize($data[$key]['meta_value']);
      }catch(Exception $e) {
        $data[$key]['meta_value'] = $data[$key]['meta_value'];
      }
      
      $dataParse[$value['meta_key']] = $data[$key];
    }
    $mysql->disconect();
    return $dataParse;
  }
  
}