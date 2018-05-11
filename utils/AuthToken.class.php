<?php
class AuthToken {
  private static $phrase = "il mondo sarà governato dai maiali nel 1984 e tutto sarà @@@ bellissimo";
  
  public static function getToken($userId) {
    $token = self::$phrase."#".(time() * rand(1, 20))."#".$userId;
    return sha1($token);
  }
  
}