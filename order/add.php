<?php
require_once "../config.php";
require_once "../model/User.model.php";
require_once "../model/Token.model.php";
require_once "../model/Order.model.php";
require_once "../model/OrderItem.model.php";
require_once "../model/OrderItemData.model.php";
require_once "../model/Product.model.php";

extract($_REQUEST);

$response = AuthToken::checkTokenUser($userid, $token);

if (!isset($listProducts) && !isset($mail)) {
  $response['status'] = StatusResponse::RES_BAD_REQUEST;
  $response['msg'][] = "list product required";
} else {
  $listProducts = json_decode($listProducts, JSON_OBJECT_AS_ARRAY);
  if (empty($listProducts)) {
    $response['status'] = StatusResponse::RES_BAD_REQUEST;
    $response['msg'][] = "list product not empty";
  }
}

if ($response['status'] == ""){
  $orderId = Order::insert($userid);
  if ($orderId) {
    $order = Order::getById($orderId);
    foreach ($listProducts as $key => $value) {
      $product = Product::getById($key);
      $orderItemId = OrderItem::insert($product['ID'], $orderId);
      if ($orderItemId) {
        OrderItemData::insert($orderItemId, '_product_id', $product['ID']);
        OrderItemData::insert($orderItemId, '_variation_id', $value['variationId']);
        OrderItemData::insert($orderItemId, '_qty', $value['qty']);
        OrderItemData::insert($orderItemId, '_tax_class', $value['taxClass']);
        OrderItemData::insert($orderItemId, '_line_subtotal', $value['lineSubtotal']);
        OrderItemData::insert($orderItemId, '_line_subtotal_tax', $value['lineSubtotalTax']);
        OrderItemData::insert($orderItemId, '_line_total', $value['lineTotal']);
        OrderItemData::insert($orderItemId, '_line_tax', $value['lineTax']);
        OrderItemData::insert($orderItemId, '_line_tax_data', serialize($value['lineTaxData']));
        OrderItemData::insert($orderItemId, '_ebs_start_format', $value['ebsStartFormat']);
        OrderItemData::insert($orderItemId, '_ebs_end_format', $value['ebsEndFormat']);
        OrderItemData::insert($orderItemId, '_booking_status', $value['bookingStatus']);
      } else {
        $response['status'] = StatusResponse::RES_ERROR_DB;
        $response['msg'][] = "Insert order filed 2";
      }
    }
    $response['status'] = StatusResponse::RES_OK;
    $response['msg'][] = "ok";
  } else {
    $response['status'] = StatusResponse::RES_ERROR_DB;
    $response['msg'][] = "Insert order filed 3";
  }
}

$response = json_encode($response, JSON_FORCE_OBJECT);
echo $response;
?>