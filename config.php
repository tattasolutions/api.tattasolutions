<?php
require_once "utils/AuthToken.class.php";
require_once "utils/StatusResponse.class.php";
require_once "utils/PasswordHash.class.php";

const PRINT_QUERY = false;

const PREFIX_TABLE = "u4y_";
const EXPIRE_PERIOD = 10*24*60*60;
if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
  define('DB_HOST', 'localhost');
  define('DB_USER', 'unmanned_api');
  define('DB_PASS', 'eGSFZ9Op#NZ%');
  define('DB_NAME', 'unmanned_database');
} else {
  define('DB_HOST', 'localhost');
  define('DB_USER', 'unmanned_api');
  define('DB_PASS', 'eGSFZ9Op#NZ%');
  define('DB_NAME', 'unmanned_database');
}


function p($data) {
  echo "<pre>";
  print_r($data);
  echo "</pre>";
}