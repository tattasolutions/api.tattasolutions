<?php
require_once "../config.php";
require_once "../db/MysqlDb.class.php";

class AuthToken {
  private static $phrase = "il mondo sarà governato dai maiali nel 1984 e tutto sarà @@@ bellissimo";
  
  public static function getToken($userId) {
    $token = self::$phrase."#".(time() * rand(1, 20))."#".$userId;
    return sha1($token);
  }
  
  public static function isExpire($tokenExpire) {
    return strtotime($tokenExpire) < strtotime(date('Y-m-d'));
  }
  
  public static function checkTokenUser($userid, $token) {
    $response = [];
    $response['status'] = "";

    //--- validazione ---
    if (!isset($token)) {
      $response['status'] = StatusResponse::RES_BAD_REQUEST;
      $response['msg'][] = "token required";
    }
  
    if (!isset($userid)) {
      $response['status'] = StatusResponse::RES_BAD_REQUEST;
      $response['msg'][] = "userid required";
    }
  
    if ($response['status'] == "") {
      $tokenReg = Token::getTokenByUserId($userid);
      
      if (!$tokenReg || $tokenReg['token'] != $token) {
        $response['status'] = StatusResponse::RES_TOKEN_INVALID;
        $response['msg'][] = "invalid token";
      } else if (AuthToken::isExpire($tokenReg['expire'])) {
        $response['status'] = StatusResponse::RES_TOKEN_EXPIRE;
        $response['msg'][] = "expire token";
      }
    }
    
    return $response;
  }
  
}