<?php
require_once "../config.php";
require_once "../model/RateOrder.model.php";
require_once "../utils/callApi.class.php";


extract($_REQUEST);

$response = [];
$response['status'] = "";

if (!isset($idUser)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "idUser required";
}

if ($response['status'] == ""){
  
  $rateOrder = RateOrder::getByUserId($idUser, true);
  
  if($rateOrder){
    
    //info user
    $url = SITE_URL . API_URL . "customers/" . $idUser . "?consumer_key=" . CONSUMER_KEY . "&consumer_secret=" . CONSUMER_SECRET;
    $user = CallAPI("GET", $url);
    $user = json_decode($user, true);
    $rateOrder['user'] = $user;
    
    
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