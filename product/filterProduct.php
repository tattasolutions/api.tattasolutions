<?php
require_once "../config.php";
require_once "../utils/callApi.class.php";

$filter = $_REQUEST;

$language = isset($_REQUEST["lang"]) ? $_REQUEST["lang"] : DEFAULT_LANG;
$perPage = (isset($filter["per_page"])) ? $filter["per_page"] : 100;

$url = SITE_URL . API_URL . "products/?category=17&per_page=" . $perPage . "&" . CONSUMER_AUTH . "&lang=" . $language;
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
  
  //if(count($product['attributes'])==0) {
  //  continue;
  //}
  
  for($j=0; $j<count($product['attributes']); $j++) {
    $attribute = $product['attributes'][$j];
    $product['attributesParse'][$attribute['name']] = $attribute['options'][0];
  }
  
  //-------------------------------
  //--- hardware ------------------
  //-------------------------------
  if(isset($filter["Type"]) && (!isset($product['attributesParse']['Type']) || $filter["Type"]!=$product['attributesParse']['Type'])){
    continue;
  }

  //--- Weight ---
  if(isset($filter["Weight"]) && (!isset($product['attributesParse']['Weight']) || $filter["Weight"]!=$product['attributesParse']['Weight'])){
    continue;
  }
  
  if(isset($filter["WeightMin"]) && (!isset($product['attributesParse']['Weight']) || $filter["WeightMin"]>$product['attributesParse']['Weight'])){
    continue;
  }
  
  if(isset($filter["WeightMax"]) && (!isset($product['attributesParse']['Weight']) || $filter["WeightMax"]<$product['attributesParse']['Weight'])){
    continue;
  }
  
  //--- Wingspan ---
  if(isset($filter["Wingspan"]) && (!isset($product['attributesParse']['Wingspan']) || $filter["Wingspan"]!=$product['attributesParse']['Wingspan'])){
    continue;
  }
  
  if(isset($filter["WingspanMin"]) && (!isset($product['attributesParse']['Wingspan']) || $filter["WingspanMin"]>$product['attributesParse']['Wingspan'])){
    continue;
  }
  
  if(isset($filter["WingspanMax"]) && (!isset($product['attributesParse']['Wingspan']) || $filter["WingspanMax"]<$product['attributesParse']['Wingspan'])){
    continue;
  }
  //-------------------------------
  //--- operation -----------------
  //-------------------------------
  //--- Endurance ---
  if(isset($filter["Endurance"]) && (!isset($product['attributesParse']['Endurance']) || $filter["Endurance"]!=$product['attributesParse']['Endurance'])){
    continue;
  }
  
  if(isset($filter["EnduranceMin"]) && (!isset($product['attributesParse']['Endurance']) || $filter["EnduranceMin"]>$product['attributesParse']['Endurance'])){
    continue;
  }
  
  if(isset($filter["EnduranceMax"]) && (!isset($product['attributesParse']['Endurance']) || $filter["EnduranceMax"]<$product['attributesParse']['Endurance'])){
    continue;
  }
  
  //--- MaximumCeiling ---
  if(isset($filter["MaximumCeiling"]) && (!isset($product['attributesParse']['MaximumCeiling']) || $filter["MaximumCeiling"]!=$product['attributesParse']['MaximumCeiling'])){
    continue;
  }
  
  if(isset($filter["MaximumCeilingMin"]) && (!isset($product['attributesParse']['MaximumCeiling']) || $filter["MaximumCeilingMin"]>$product['attributesParse']['MaximumCeiling'])){
    continue;
  }
  
  if(isset($filter["MaximumCeilingMax"]) && (!isset($product['attributesParse']['MaximumCeiling']) || $filter["MaximumCeilingMax"]<$product['attributesParse']['MaximumCeiling'])){
    continue;
  }
  
  //--- PreFlightSetupTime ---
  if(isset($filter["PreFlightSetupTime"]) && (!isset($product['attributesParse']['PreFlightSetupTime']) || $filter["PreFlightSetupTime"]!=$product['attributesParse']['PreFlightSetupTime'])){
    continue;
  }
  
  if(isset($filter["PreFlightSetupTimeMin"]) && (!isset($product['attributesParse']['PreFlightSetupTime']) || $filter["PreFlightSetupTimeMin"]>$product['attributesParse']['PreFlightSetupTime'])){
    continue;
  }
  
  if(isset($filter["PreFlightSetupTimeMax"]) && (!isset($product['attributesParse']['PreFlightSetupTime']) || $filter["PreFlightSetupTimeMax"]<$product['attributesParse']['PreFlightSetupTime'])){
    continue;
  }
  
  //--- Range ---
  if(isset($filter["Range"]) && (!isset($product['attributesParse']['Range']) || $filter["Range"]!=$product['attributesParse']['Range'])){
    continue;
  }
  
  if(isset($filter["RangeMin"]) && (!isset($product['attributesParse']['Range']) || $filter["RangeMin"]>$product['attributesParse']['Range'])){
    continue;
  }
  
  if(isset($filter["RangeMax"]) && (!isset($product['attributesParse']['Range']) || $filter["RangeMax"]<$product['attributesParse']['Range'])){
    continue;
  }
  
  //--- CruiseSpeed ---
  if(isset($filter["CruiseSpeed"]) && (!isset($product['attributesParse']['CruiseSpeed']) || $filter["CruiseSpeed"]!=$product['attributesParse']['CruiseSpeed'])){
    continue;
  }
  
  if(isset($filter["CruiseSpeedMin"]) && (!isset($product['attributesParse']['CruiseSpeed']) || $filter["CruiseSpeedMin"]>$product['attributesParse']['CruiseSpeed'])){
    continue;
  }
  
  if(isset($filter["CruiseSpeedMax"]) && (!isset($product['attributesParse']['CruiseSpeed']) || $filter["CruiseSpeedMax"]<$product['attributesParse']['CruiseSpeed'])){
    continue;
  }
  
  if(isset($filter["TakeOffType"]) && (!isset($product['attributesParse']['Take-off Type']) || $filter["TakeOffType"]!=$product['attributesParse']['Take-off Type'])){
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
