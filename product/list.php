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

if ($response['status'] == ""){
  $listProduct = Product::getListProduct();
  $listUserProduct = [];
  $listCategory = [];
  foreach ($listProduct as $keyListProduct => $valueListProduct) {
    //--- category ---
    $listProductCategory = ProductCategory::getByProduct($valueListProduct['ID']);
    foreach ($listProductCategory as $keyProductCategory => $valueProductCategory){
      $listProduct[$keyListProduct]['category'][] = $valueProductCategory['term_taxonomy_id'];
      if (!isset($listCategory[$valueProductCategory['term_taxonomy_id']])) {
        $listCategory[$valueProductCategory['term_taxonomy_id']] = Category::getById($valueProductCategory['term_taxonomy_id']);
      }
    }
    if($listProductCategory) {
      $listProduct[$keyListProduct]['category'] = $productCategory['term_taxonomy_id'];
      if (!isset($listCategory[$listProduct[$keyListProduct]['category']])) {
        $listCategory[$listProduct[$keyListProduct]['category']] = Category::getById($listProduct[$keyListProduct]['category']);
      }
    }
    //--- author ---
    $idUserProduct = $valueListProduct['post_author'];
    if (!isset($listUserProduct[$idUserProduct])) {
      $listUserProduct[$idUserProduct] = User::getById($idUserProduct);
    }
  }
  $response['status'] = StatusResponse::RES_OK;
  $response['msg'][] = "ok";
  $response['data'] = $listProduct;
  $response['vendor'] = $listUserProduct;
  $response['category'] = $listCategory;
  
}

$response = json_encode($response, JSON_FORCE_OBJECT);
echo $response;
?>