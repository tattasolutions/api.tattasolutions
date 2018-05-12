<?php
require_once "../config.php";
require_once "../model/User.model.php";
require_once "../model/Token.model.php";
require_once "../utils/Password.class.php";

extract($_REQUEST);

$response = [];
$response['status'] = "";

//--- validazione ---
if (!isset($username)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "username required";
} else {
  $data = User::getUserByUsername($username);
  if ($data) {
    $response['status'] = StatusResponse::RES_ALREADY_EXISTS;
    $response['msg'][] = "username already exists";
  }
}

if (!isset($mail)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "mail required";
} else {
  $data = User::getUserByMail($mail);
  if ($data) {
    $response['status'] = StatusResponse::RES_ALREADY_EXISTS;
    $response['msg'][] = "mail already exists";
  }
}

if (!isset($password)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "password required";
}

if (!isset($cf)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "cf/vat required";
}

if (!isset($name)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "name required";
}

if (!isset($surname)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "surname required";
}

if (!isset($address)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "address required";
}

if (!isset($birthDate)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "birthdate required";
}

if (!isset($typeUser)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "type user required";
}

if (!isset($typeManifactur)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "type manifactur required";
}

if (!isset($typePilot)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "type pilot required";
}

//MEMO UPLOAD DOC
//-------------------
if ($response['status'] == ""){
  //--- utente ---
  $niceName = $surname . "-" . $name;
  $displayName = $surname . " " . $name;
  $passwordHash = new PasswordHash(8, TRUE);
  $activationKey = time() . ":" . $passwordHash->HashPassword($password);
  $password = $passwordHash->HashPassword($password);
  $newUser = User::insert($username, $mail, $password, $cf, $name, $surname, $niceName, $displayName, $activationKey, $address, $birthDate, $typeUser, $typeManifactur, $typePilot);
  
  if ($newUser) {
    //--- dati utente ---
    //TODO inserire il resto dei dati utente
  
    //--- token ---
    $token = AuthToken::getToken($newUser['ID']);
    $expire = strtotime(date('Y-m-d', strtotime(' + 5 days')));
    $token = Token::setTokenByUserId($newUser['ID'], $token, $expire);
    $newUser['token'] = $token;
  
    $response['status'] = StatusResponse::RES_OK;
    $response['msg'][] = "ok";
    $response['data'] = $newUser;
  } else {
    $response['status'] = StatusResponse::RES_ERROR;
    $response['msg'][] = "no register user";
  }
}

$response = json_encode($response);
echo $response;

?>