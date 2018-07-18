<?php
require_once "../config.php";
require_once "../model/RateOrder.model.php";
require_once "../utils/callApi.class.php";


extract($_REQUEST);

$response = [];
$response['status'] = "";

if (!isset($idCustomer)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "idCustomer required";
}

if (!isset($idUser)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "idUser required";
}

if (!isset($idOrder)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "idOrder required";
}


if ($response['status'] == ""){
  
  $rateOrder = RateOrder::getByInfo($idCustomer, $idUser, $idOrder);
  
  if(!$rateOrder) {
    $rateOrder = RateOrder::insert($idCustomer, $idUser, $idOrder, $rate);
  
    if($rateOrder){
    
      //info customer
      $url = SITE_URL . API_URL . "customers/" . $idCustomer . "?consumer_key=" . CONSUMER_KEY . "&consumer_secret=" . CONSUMER_SECRET;
      $customer = CallAPI("GET", $url);
      $customer = json_decode($customer, true);
      $rateOrder['customer'] = $customer;
    
      //info user
      $url = SITE_URL . API_URL . "customers/" . $idUser . "?consumer_key=" . CONSUMER_KEY . "&consumer_secret=" . CONSUMER_SECRET;
      $user = CallAPI("GET", $url);
      $user = json_decode($user, true);
      $rateOrder['user'] = $user;
  
      //info order
      $url = SITE_URL . API_URL . "orders/" . $idOrder . "?consumer_key=" . CONSUMER_KEY . "&consumer_secret=" . CONSUMER_SECRET;
      $order = CallAPI("GET", $url);
      $order = json_decode($order, true);
      $rateOrder['order'] = $order;
      
      $response['status'] = StatusResponse::RES_OK;
      $response['msg'][] = "ok";
      $response['data'] = $rateOrder;
    } else {
      $response['status'] = StatusResponse::RES_NO_AUTH;
      $response['msg'][] = "wrong result";
    }
  } else {
    $response['status'] = StatusResponse::RES_ALREADY_EXISTS;
    $response['msg'][] = "rate already exists";
  }
  
  
}

$response = json_encode($response);
echo $response;

?>