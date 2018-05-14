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
  $response['msg'][] = "password required";
}


if ($response['status'] == ""){
  if (isset($username)){
    $data = User::getUserByUsername($username);
  } else if (isset($mail)){
    $data = User::getUserByMail($mail);
  }
  
   if ($data) {
  
     $passwordHash = new PasswordHash(8, TRUE);
     if(!$passwordHash->CheckPassword($password, $data['user_pass'])) {
       $response['status'] = StatusResponse::RES_NO_AUTH;
       $response['msg'][] = "wrong credetial";
     } else {
       $token = Token::getTokenByUserId($data['ID']);
       if (!$token || AuthToken::isExpire($token['expire'])) {
         Token::deleteTokenByUserId($data['ID']);
         $token = AuthToken::getToken($data['ID']);
         $expire = strtotime(date('Y-m-d', strtotime(' + 5 days')));
         $token = Token::setTokenByUserId($data['ID'], $token, $expire);
       }
       
       //--- profile ---
       $profile = Profile::getUserProfile($data['ID']);
  
       $data['profile'] = $profile;
       $data['token'] = $token;
       $response['status'] = StatusResponse::RES_OK;
       $response['msg'][] = "ok";
       $response['data'] = $data;
     }
    

  } else {
    $response['status'] = StatusResponse::RES_NO_AUTH;
    $response['msg'][] = "wrong credetial";
  }
}

$response = json_encode($response);
echo $response;

?>