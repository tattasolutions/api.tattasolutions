<?php
require_once "../config.php";
require_once "../model/UserLocation.model.php";
require_once "../utils/callApi.class.php";

extract($_REQUEST);

$response = [];
$response['status'] = "";

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
}


if ($response['status'] == ""){
  
  
  $url = SITE_URL . API_URL . "products/?category=16&consumer_key=" . CONSUMER_KEY . "&consumer_secret=" . CONSUMER_SECRET;
  $listProduct = CallAPI("GET", $url);
  
  $listProduct = json_decode($listProduct, true);
  
  if ($listProduct) {
    $listUserProductSelect = [];
    foreach ($listProduct as $keyProduct => $product) {
      foreach ($product['meta_data'] as $keyMeta => $meta) {
        if ($meta['key'] == "eg-user") {
          $idUser = $meta['value'];
          $userLocation = UserLocation::getByUserId($idUser);
          
          if ($userLocation) {
            $lon        = $userLocation["lon"];
            $lat        = $userLocation["lat"];
            $lonStart   = $lon - $delta;
            $lonEnd     = $lon + $delta;
            $latStart   = $lat - $delta;
            $latEnd     = $lat + $delta;
            
            if ($lon >= $lonStart && $lon <= $lonEnd && $lat >= $latStart && $lat <= $latEnd) {
              $listUserProductSelect[$idUser][] = $product;
            }
          }
        }
      }
    }
    
     $response['status'] = StatusResponse::RES_OK;
     $response['msg'][] = "ok";
     $response['data'] = $listUserProductSelect;
   } else {
    $response['status'] = StatusResponse::RES_NO_AUTH;
    $response['msg'][] = "wrong credetial";
  }
}

$response = json_encode($response);
echo $response;

?>