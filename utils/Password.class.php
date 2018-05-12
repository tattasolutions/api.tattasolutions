<?php
/**
 * Created by PhpStorm.
 * User: belfus
 * Date: 12/05/18
 * Time: 14:57
 */

class Password {
  private static $lung_pass = 10;
  
  public static function generate() {
    $mypass = "";
    for ($x=1; $x<=self::$lung_pass; $x++){
      // Se $x è multiplo di 2...
      if ($x % 2){
        $mypass .= chr(rand(97,122));
      }else{
        $mypass .= rand(0,9);
      }
    }
    return $mypass;
  }
}