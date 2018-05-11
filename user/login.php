<?php
require_once "../db/MysqlDb.class.php";
require_once "../utils/AuthToken.class.php";
require_once "../utils/StatusResponse.class.php";

$mysql = new MysqlDb();
$mysql->conect();

extract($_REQUEST);

$response = [];
if (!isset($username) || !isset($password)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'] = "username or password required";
  $response = json_encode($response);
} else {
  $data = $mysql->fetch_array('SELECT * FROM u4y_signups WHERE user_login="' . $username . '"');
  $response['status'] = StatusResponse::RES_OK;
  $response['msg'] = "ok";
  $response['data'] = $data;
  $response = json_encode($response);
}

echo $response;

$mysql->disconect();