<?php
require_once "../config.php";
require_once "../utils/callApi.class.php";

$filter = $_REQUEST;

$url = SITE_URL . API_URL . "products/?consumer_key=" . CONSUMER_KEY . "&consumer_secret=" . CONSUMER_SECRET;
$listProduct = CallAPI("GET", $url);
$listProduct = json_decode($listProduct, true);

$listProductFilter = [];
for($i=0; $i<count($listProduct); $i++){
  $product = $listProduct[$i];
  
  //--- price ---
  if (isset($filter['min_price']) && $product['regular_price']<$filter['min_price']) {
    continue;
  }
  
  if (isset($filter['max_price']) && $product['regular_price']>$filter['max_price']) {
    continue;
  }
  
  if(count($product['attributes'])==0) {
    continue;
  }
  
  for($j=0; $j<count($product['attributes']); $j++) {
    $attribute = $product['attributes'][$j];
  
    $product['attributesParse'][$attribute['name']] = $attribute['options'];
  }
  
  //--- hardware ---
  if(isset($filter["Type"]) && (!isset($product['attributesParse']['Type']) || !in_array($filter["Type"], $product['attributesParse']['Type']))){
    continue;
  }
  
  if(isset($filter["Weight"]) && (!isset($product['attributesParse']['Weight']) || !in_array($filter["Weight"], $product['attributesParse']['Weight']))){
    continue;
  }
  
  if(isset($filter["Wingspan"]) && (!isset($product['attributesParse']['Wingspan']) || !in_array($filter["Wingspan"], $product['attributesParse']['Wingspan']))){
    continue;
  }

  if(isset($filter["WingspanMin"])){
    $found = false;
    foreach ($product['attributesParse']['Wingspan'] as $key => $wingspanValue) {
      if ($wingspanValue >= $filter["WingspanMin"]) {
        if(isset($filter["WingspanMax"])) {
          if ($wingspanValue <= $filter["WingspanMax"]) {
            $found = true;
          }
        } else {
          $found = true;
        }
      }
    }
    
    if(!$found) {
      continue;
    }
  }
  
  if(isset($filter["WingspanMax"])){
    $found = false;
    foreach ($product['attributesParse']['Wingspan'] as $key => $wingspanValue) {
      if ($wingspanValue <= $filter["WingspanMax"]) {
        if(isset($filter["WingspanMin"])) {
          if ($wingspanValue >= $filter["WingspanMin"]) {
            $found = true;
          }
        } else {
          $found = true;
        }
      }
    }
    
    if(!$found) {
      continue;
    }
  }
  
  //--- operation ---
  if(isset($filter["Endurance"]) && (!isset($product['attributesParse']['Endurance']) || !in_array($filter["Endurance"], $product['attributesParse']['Endurance']))){
    continue;
  }
  
  if(isset($filter["MaximumCeiling"]) && (!isset($product['attributesParse']['Maximum Ceiling']) || !in_array($filter["MaximumCeiling"], $product['attributesParse']['Maximum Ceiling']))){
    continue;
  }
  
  if(isset($filter["PreFlightSetupTime"]) && (!isset($product['attributesParse']['Pre-flight setup time']) || !in_array($filter["PreFlightSetupTime"], $product['attributesParse']['Pre-flight setup time']))){
    continue;
  }
  
  if(isset($filter["Range"]) && (!isset($product['attributesParse']['Range']) || !in_array($filter["Range"], $product['attributesParse']['Range']))){
    continue;
  }
  
  if(isset($filter["CruiseSpeed"]) && (!isset($product['attributesParse']['Cruise Speed']) || !in_array($filter["CruiseSpeed"], $product['attributesParse']['Cruise Speed']))){
    continue;
  }
  
  if(isset($filter["TakeOffType"]) && (!isset($product['attributesParse']['Take-off Type']) || !in_array($filter["TakeOffType"], $product['attributesParse']['Take-off Type']))){
    continue;
  }
  
  
  $listProductFilter[] = $product;
}

if(empty($listProductFilter)) {
  $response['status'] = StatusResponse::RES_NO_RESULT;
  $response['msg'][] = "not exists product";
} else {
  $response['status'] = StatusResponse::RES_OK;
  $response['msg'][] = "ok";
  $response['data'] = $listProductFilter;
}

header('Content-Type: application/json');
echo json_encode($response);
