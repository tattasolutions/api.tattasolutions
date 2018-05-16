<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";
require_once "Model.php";


class ProfileMetaData extends Model{
  
  const TABLE  = PREFIX_TABLE . "bp_xprofile_fields";
  
  const FIELD_ID_CF_VAT     = 43;
  const FIELD_ID_NAME       = 1;
  const FIELD_ID_SURNAME    = 45;
  const FIELD_ID_ADDRESS    = 46;
  const FIELD_ID_BIRTHDATE  = 47;
  const FIELD_ID_USER       = 48;
  const FIELD_ID_MANUFACTUR = 51;
  const FIELD_ID_PILOT      = 54;
  
  function getProfileMetaData() {
    $mysql = new MysqlDb();
    $mysql->conect();
  
    $query = "SELECT * FROM " . self::TABLE . " WHERE parent_id=0";
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
  
  function getListValue($parentId) {
    $mysql = new MysqlDb();
    $mysql->conect();
    
    $query = "SELECT * FROM " . self::TABLE . " WHERE parent_id=" . $parentId;
    self::printQuery($query);
    $data = $mysql->fetch_single($query);
    $mysql->disconect();
    return $data;
  }
}