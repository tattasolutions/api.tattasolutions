<?php
require_once "../config.php";
require_once "../model/User.model.php";
require_once "../model/Token.model.php";
require_once "../model/Product.model.php";
require_once "../model/ProductMeta.model.php";

extract($_REQUEST);

$response = AuthToken::checkTokenUser($userid, $token);

if ($response['status'] == ""){
  $listProduct = Product::getListProduct();
  $listUserProduct = [];
  foreach ($listProduct as $key => $product) {
    //--- meta product ---
    $ProductMeta = ProductMeta::getProductMetaByProductId($product['ID']);
    foreach ($metaProduct as $key2 => $value2) {
      try{
        $metaProduct[$key2]['meta_value'] = unserialize($metaProduct[$key2]['meta_value']);
      } catch (Exception $e) {
        $metaProduct[$key2]['meta_value'] = $metaProduct[$key2]['meta_value'];
      }
      
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

$response = json_encode($response, JSON_FORCE_OBJECT);
echo $response;
?>