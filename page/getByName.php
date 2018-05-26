<?php
require_once "../config.php";
require_once "../model/User.model.php";
require_once "../model/Token.model.php";
require_once "../model/Page.model.php";
require_once "../model/PageMeta.model.php";
require_once "../utils/JsonErrorUtf8.class.php";

extract($_REQUEST);

$response = AuthToken::checkTokenUser($userid, $token);

if (!isset($name)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "name page required";
}

if ($response['status'] == ""){
  $page = Page::getByName($name);
  
  if ($page) {
    //--- meta page ---
    $page['metaData'] = PageMeta::getPageMetaByPageId($page['ID']);
  
    //--- author ---
    $idUserPage = $page['post_author'];
    if (!isset($listUserPage[$idUserPage])) {
      $userPage = User::getById($idUserPage);
    }
  
    $response['status'] = StatusResponse::RES_OK;
    $response['msg'][] = "ok";
    $response['data'] = $page;
    $response['vendor'] = $userPage;
  } else {
    $response['status'] = StatusResponse::RES_NO_RESULT;
    $response['msg'][] = "page for id not found";
  }
}


$response = json_encode(JsonErrorUtf8::utf8ize($response), JSON_FORCE_OBJECT);
echo $response;
?>