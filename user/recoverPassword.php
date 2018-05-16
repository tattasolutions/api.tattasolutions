<?php
require_once "../config.php";
require_once "../model/User.model.php";
require_once "../model/Token.model.php";
require_once "../utils/Password.class.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/PHPMailer/src/Exception.php';
require '../vendor/PHPMailer/src/PHPMailer.php';
require '../vendor/PHPMailer/src/SMTP.php';


require_once "../vendor/PHPMailer/src/PHPMailer.php";

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
  
      //--- invio mail ---
      $messaggio = new PHPMailer;
      /*$messaggio->isSMTP();
      $messaggio->Host = 'smtp.gmail.com';
      $messaggio->SMTPAuth = true;
      $messaggio->Username = 'belfiore.giovanni@gmail.com';
      $messaggio->Password = '';
      $messaggio->SMTPSecure = 'tls';
      $messaggio->Port = 587;*/
      $messaggio->setFrom(MITTENTE_MAIL, MITTENTE_NAME);
      $messaggio->addAddress($mail, $data['user_login']);
      $messaggio->Subject  = 'UnManned4You - Recover Password';
      $messaggio->Body     = "Username: " . $data['user_login'] . ", Password: " . $newPassword . ", Mail: " . $data['user_email'];
      if(!$messaggio->send()) {
        $response['sendMail']['status'] = false;
        $response['sendMail']['message'] = "Mailer error: " . $messaggio->ErrorInfo;
      } else {
        $response['sendMail']['status'] = true;
        $response['sendMail']['message'] = "ok";
      }
      //------------------
      
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