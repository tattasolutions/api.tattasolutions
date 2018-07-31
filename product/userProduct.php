<?php
require_once "../config.php";
require_once "../utils/callApi.class.php";

extract($_REQUEST);
$language = isset($_REQUEST["lang"]) ? $_REQUEST["lang"] : DEFAULT_LANG;
$perPage = (isset($filter["per_page"])) ? $filter["per_page"] : 100;

if (!isset($idCustomer)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "idCustomer required";
}

if ($response['status'] == ""){
  $url = SITE_URL . API_URL . "products/?per_page=" . $perPage . "&consumer_key=" . CONSUMER_KEY . "&consumer_secret=" . CONSUMER_SECRET . "&lang=" . $language;
  $listProduct = CallAPI("GET", $url);
  $listProduct = json_decode($listProduct, true);
  
  $listProductUser = [];
  for($i=0; $i<count($listProduct); $i++){
    $product = $listProduct[$i];
    
    for($j=0; $j<count($product["meta_data"]); $j++) {
      $metaData = $product["meta_data"][$j];
      if ($metaData['key'] == "eg-user" && $metaData["value"] == $idCustomer) {
        $listProductUser[] = $product;
        break;
      }
    }
  }
  
  if(empty($listProductUser)) {
    $response['status'] = StatusResponse::RES_NO_RESULT;
    $response['msg'][] = "not exists product";
  } else {
    $response['status'] = StatusResponse::RES_OK;
    $response['msg'][] = "ok";
    $response['data'] = $listProductUser;
  }
}


header('Content-Type: application/json');
echo json_encode($response);
?>