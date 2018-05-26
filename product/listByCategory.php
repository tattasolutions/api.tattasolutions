<?php
require_once "../config.php";
require_once "../model/User.model.php";
require_once "../model/Token.model.php";
require_once "../model/Product.model.php";
require_once "../model/ProductMeta.model.php";
require_once "../model/ProductCategory.model.php";
require_once "../model/Category.model.php";

extract($_REQUEST);

$response = AuthToken::checkTokenUser($userid, $token);

if (!isset($category)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "category required";
}

if ($response['status'] == ""){
  
  $categoryObj = Category::getBySlug($category);
  if($categoryObj) {
    $listProductCategory = ProductCategory::getByCategory($categoryObj['term_id']);
  
    if ($listProductCategory) {
      $listProduct = [];
      $listUserProduct = [];
      foreach ($listProductCategory as $key => $productCategory) {
        $product = Product::getById($productCategory['object_id']);
      
        //--- meta product ---
        $metaProduct = ProductMeta::getProductMetaByProductId($product['ID']);
        foreach ($metaProduct as $key2 => $value2) {
          try{
            $metaProduct[$key2]['meta_value'] = unserialize($metaProduct[$key2]['meta_value']);
          } catch (Exception $e) {
            $metaProduct[$key2]['meta_value'] = $metaProduct[$key2]['meta_value'];
          }
        }
      
        $product['metaData'] = $metaProduct;
        $listProduct[] = $product;
      
        //--- author ---
        $idUserProduct = $product['post_author'];
        if (!isset($listUserProduct[$idUserProduct])) {
          $listUserProduct[$idUserProduct] = User::getById($idUserProduct);
        }
      }
    } else {
      $response['status'] = StatusResponse::RES_NO_RESULT;
      $response['msg'][] = "no product for category";
    }
  } else {
    $response['status'] = StatusResponse::RES_NO_RESULT;
    $response['msg'][] = "no product for category";
  }
  
  
  $response['status'] = StatusResponse::RES_OK;
  $response['msg'][] = "ok";
  $response['data'] = $listProduct;
  $response['vendor'] = $listUserProduct;
  $response['category'] = $categoryObj;
  
}

$response = json_encode($response, JSON_FORCE_OBJECT);
echo $response;
?>