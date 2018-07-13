<?php
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ERROR);

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


const MITTENTE_MAIL = "info@unmanned4you.com";
const MITTENTE_NAME = "UnManned4You not replay";

const SITE_URL = "https://unmanned4you.it";
const API_URL = "/wp-json/wc/v2/";

const CONSUMER_KEY = "ck_0fa573af68d2c5b9cbdcccb995c437add0cf6b40";
const CONSUMER_SECRET = "cs_9ea01da039ab9e8d9aacdf9ed537d79cb9b05b30";

function p($data) {
  echo "<pre>";
  print_r($data);
  echo "</pre>";
}