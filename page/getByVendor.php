<?php
require_once "../config.php";
require_once "../model/User.model.php";
require_once "../model/Token.model.php";
require_once "../model/Page.model.php";
require_once "../model/PageMeta.model.php";
require_once "../utils/JsonErrorUtf8.class.php";

extract($_REQUEST);

$response = AuthToken::checkTokenUser($userid, $token);

if (!isset($vendor)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "vendor required";
}

if ($response['status'] == ""){
  $listPage = Page::getByVendor($vendor);
  $listUserPage = [];
  foreach ($listPage as $key => $page) {
    //--- meta page ---
    $listPage[$key]['metaData'] = PageMeta::getPageMetaByPageId($page['ID']);
    
    //--- author ---
    $idUserPage = $page['post_author'];
    if (!isset($listUserPage[$idUserPage])) {
      $listUserPage[$idUserPage] = User::getById($idUserPage);
    }
  }
  
  $response['status'] = StatusResponse::RES_OK;
  $response['msg'][] = "ok";
  $response['data'] = $listPage;
  $response['vendor'] = $listUserPage;
}


$response = json_encode(JsonErrorUtf8::utf8ize($response), JSON_FORCE_OBJECT);
echo $response;
?>