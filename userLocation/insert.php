<?php
require_once "../config.php";
require_once "../model/UserLocation.model.php";

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

if (!isset($userId)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "user id required";
}


if ($response['status'] == ""){

  $userLocation = UserLocation::getByUserId($userId);
  
  if($userLocation){
    $userLocation = UserLocation::update($userId, $lat, $lon);
  } else {
    $userLocation = UserLocation::insert($userId, $lat, $lon);
  }
  
  if ($userLocation) {
    
     $response['status'] = StatusResponse::RES_OK;
     $response['msg'][] = "ok";
     $response['data'] = $userLocation;
   } else {
    $response['status'] = StatusResponse::RES_NO_AUTH;
    $response['msg'][] = "wrong result";
  }
}

$response = json_encode($response);
echo $response;

?>