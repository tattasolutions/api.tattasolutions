<?php
require_once "callApi.class.php";

$filter = $_REQUEST;

$url = "https://unmanned4you.it/wp-json/wc/v2/products/?consumer_key=ck_0fa573af68d2c5b9cbdcccb995c437add0cf6b40&consumer_secret=cs_9ea01da039ab9e8d9aacdf9ed537d79cb9b05b30";
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

header('Content-Type: application/json');
echo json_encode($listProductFilter);
