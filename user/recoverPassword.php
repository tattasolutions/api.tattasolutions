<?php
require_once "../config.php";
require_once "../model/User.model.php";
require_once "../model/Token.model.php";
require_once "../utils/Password.class.php";

extract($_REQUEST);

$response = [];
$response['status'] = "";

if (!isset($mail)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "mail required";
}

 if ($response['status'] == ""){
  
  $data = User::getUserByMail($mail);
  if ($data) {
  
    $passwordHash = new PasswordHash(8, TRUE);
    
    $newPassword = trim(Password::generate());
    #$newPassword = "suino2018!";
    $newPasswordHash = $passwordHash->HashPassword($newPassword);
    if (User::updatePassword($data['ID'], $newPasswordHash)) {
      Token::deleteTokenByUserId($data['ID']);
  
      mail($data['user_email'], 'Recover Password', "Username: " . $data['user_login'] . ", Password: " . $newPassword . ", Mail: " . $data['user_email']);
      
      $response['status'] = StatusResponse::RES_OK;
      $response['msg'][] = "ok";
    } else {
      $response['status'] = StatusResponse::RES_ERROR;
      $response['msg'][] = "error update password";
    }

  } else {
    $response['status'] = StatusResponse::RES_NO_RESULT;
    $response['msg'][] = "no mail exists";
  }
}

$response = json_encode($response);
echo $response;

?>