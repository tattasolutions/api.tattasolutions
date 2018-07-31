<?php
require_once "../config.php";
require_once "../model/RateOrder.model.php";
require_once "../utils/callApi.class.php";


extract($_REQUEST);

$language = isset($_REQUEST["lang"]) ? $_REQUEST["lang"] : DEFAULT_LANG;

$response = [];
$response['status'] = "";

if (!isset($idCustomer)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "idCustomer required";
}

if ($response['status'] == ""){

  $rateOrder = RateOrder::getByCustomerId($idCustomer, true);
  
  if($rateOrder){
  
    //info customer
    $url = SITE_URL . API_URL . "customers/" . $idCustomer . "?consumer_key=" . CONSUMER_KEY . "&consumer_secret=" . CONSUMER_SECRET . "&lang=" . $language;
    $customer = CallAPI("GET", $url);
    $customer = json_decode($customer, true);
    $rateOrder['customer'] = $customer;
  
    $response['status'] = StatusResponse::RES_OK;
    $response['msg'][] = "ok";
    $response['data'] = $rateOrder;
  } else {
    $response['status'] = StatusResponse::RES_NO_AUTH;
    $response['msg'][] = "wrong result";
  }
}

$response = json_encode($response);
echo $response;

?>