<?php
require_once "../utils/AuthToken.class.php";
require_once "../utils/StatusResponse.class.php";
require_once "../utils/PasswordHash.class.php";


const PREFIX_TABLE = "u4y_";
const PRINT_QUERY = false;
const EXPIRE_PERIOD = 10*24*60*60;


function p($data) {
  echo "<pre>";
  print_r($data);
  echo "</pre>";
}