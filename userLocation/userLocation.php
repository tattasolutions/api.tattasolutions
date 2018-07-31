<?php
require_once "../config.php";
require_once "../model/UserLocation.model.php";
require_once "../model/RateOrder.model.php";
require_once "../utils/callApi.class.php";

extract($_REQUEST);

$response = [];
$response['status'] = "";

$language = isset($_REQUEST["lang"]) ? $_REQUEST["lang"] : DEFAULT_LANG;
$perPage = (isset($_REQUEST["per_page"])) ? $_REQUEST["per_page"] : 100;

/*
if (!isset($lat)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "lat required";
}

if (!isset($lon)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "lon required";
}

if (!isset($delta)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "delta required";
}*/


if ($response['status'] == "") {
  
  //list service rent (16)
  $url = SITE_URL . API_URL . "products/?per_page=" . $perPage . "&category=16&consumer_key=" . CONSUMER_KEY . "&consumer_secret=" . CONSUMER_SECRET . "&lang=" . $language;
  $listProduct = CallAPI("GET", $url);
  $listProduct = json_decode($listProduct, true);
  
  if ($listProduct) {
    
    /*$lonStart = $lon - $delta;
    $lonEnd = $lon + $delta;
    $latStart = $lat - $delta;
    $latEnd = $lat + $delta;*/
  
    $raggio = 6378137;
    $delta = $delta * 1000;
  
    $dLat = $delta/$raggio;
    $dLon = $delta/($raggio * cos(pi()*$lat/180));
  
    $lonStart = $lon - $dLon * 180 / pi();
    $lonEnd   = $lon + $dLon * 180 / pi();
    $latStart = $lat - $dLat * 180 / pi();
    $latEnd   = $lat + $dLat * 180 / pi();
	
	  //$listUser = UserLocation::getByPosition($lonStart, $lonEnd, $latStart, $latEnd);
	  $listUser = UserLocation::getAll();
	
	  $listResult = [];
    
    foreach ($listUser as $keyListUser => $valueListUser) {
      
      foreach ($listProduct as $keyProduct => $product) {
        
        $idUser = findIdUserFromMetaProduct($product['meta_data']);
        
        if ($idUser == $valueListUser['idUser']) {
	
          //info user
          $url = SITE_URL . API_URL . "customers/" . $idUser . "?consumer_key=" . CONSUMER_KEY . "&consumer_secret=" . CONSUMER_SECRET . "&lang=" . $language;
          $user = CallAPI("GET", $url);
          $user = json_decode($user, true);
          
          if ($user) {
            
            if (isset($listResult[$idUser])) {
              $singleResult = $listResult[$idUser];
              $singleResult['products'][$product['id']] = $product;
            } else {
              $singleResult = [];
              $singleResult['user'] = $user;
              $singleResult['position'] = $valueListUser;
              $singleResult['products'] = [];
              $singleResult['products'][$product['id']] = $product;
              $singleResult['rate'] = RateOrder::getByCustomerId($idUser, true);
            }
  
            $listResult[$idUser] = $singleResult;
          }
        }
      }
    }
    $response['status'] = StatusResponse::RES_OK;
    $response['msg'][] = "ok";
    $response['data'] = $listResult;
    
  }else {
    $response['status'] = StatusResponse::RES_NO_AUTH;
    $response['msg'][] = "not exists rent product";
  }
}

$response = json_encode($response);
header('Content-Type: application/json');
echo $response;


function findIdUserFromMetaProduct($metaProduct) {
  
  foreach ($metaProduct as $key => $value) {
    
    if ($value['key'] == "eg-user") {
      $idUser = $value['value'];
      return $idUser;
      
    }
  }
  
  return null;
}

?>