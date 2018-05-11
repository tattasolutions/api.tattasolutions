<?php
require_once "../config.php";
require_once "../model/User.model.php";
require_once "../model/Token.model.php";

extract($_REQUEST);

$response = [];
$response['status'] = "";

if (!isset($username) && !isset($mail)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "username or mail required";
}

if (!isset($password)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'] = "password required";
}


 if ($response['status'] == ""){
  if (isset($username)){
    $data = User::getUserByUsernamePassword($username, $password);
  } else if (isset($mail)){
    $data = User::getUserByMailPassword($mail, $password);
  }
  
   if ($data) {
    
    $token = Token::getTokenByUserId($data['ID']);
    if (!$token || $token['expire'] < strtotime(date('Y-m-d'))) {
      $token = AuthToken::getToken($data['ID']);
      $expire = strtotime(date('Y-m-d', strtotime(' + 5 days')));
      Token::setTokenByUserId($data['ID'], $token, $expire);
    } else {
      $token = $token['token'];
    }
    
    $data['token'] = $token;
    $response['status'] = StatusResponse::RES_OK;
    $response['msg'] = "ok";
    $response['data'] = $data;
  } else {
    $response['status'] = StatusResponse::RES_NO_RESULT;
    $response['msg'] = "no result";
  }
}

$response = json_encode($response);
echo $response;

?>