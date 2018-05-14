<?php
require_once "../config.php";

class Model {
  const TABLE = "";
  public static function printQuery($query) {
    if (PRINT_QUERY) {
      echo "<br>" . $query;
    }
  }
  
  public static function getById($id) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE ID='" . $id . "'";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
}
?>