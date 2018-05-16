<?php
require_once "../config.php";
require_once "../model/User.model.php";
require_once "../model/Token.model.php";
require_once "../model/Posts.model.php";
require_once "../model/MetaPosts.model.php";

extract($_REQUEST);

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

if ($response['status'] == ""){
  $tokenReg = Token::getTokenByUserId($userid);
  if (!$tokenReg || $tokenReg['token'] != $token) {
    $response['status'] = StatusResponse::RES_TOKEN_INVALID;
    $response['msg'][] = "invalid token";
  } else if(AuthToken::isExpire($tokenReg['expire'])) {
    $response['status'] = StatusResponse::RES_TOKEN_EXPIRE;
    $response['msg'][] = "expire token";
  } else {
    $listProduct = Posts::getProduct();
    $listUserProduct = [];
    foreach ($listProduct as $key => $product) {
      //--- meta product ---
      $metaProduct = MetaPosts::getMetaProductByProductId($product['ID']);
      foreach ($metaProduct as $key2 => $value2) {
        $metaProduct[$key2]['meta_value'] = unserialize($metaProduct[$key2]['meta_value']);
      }
      $listProduct[$key]['metaData'] = $metaProduct;
      
      //--- author ---
      $idUserProduct = $product['post_author'];
      if (!isset($listUserProduct[$idUserProduct])) {
        $listUserProduct[$idUserProduct] = User::getById($idUserProduct);
      }
    }
    $response['status'] = StatusResponse::RES_OK;
    $response['msg'][] = "ok";
    $response['data'] = $listProduct;
    $response['vendor'] = $listUserProduct;
  }
}

$response = json_encode($response, JSON_FORCE_OBJECT);
echo $response;
?>