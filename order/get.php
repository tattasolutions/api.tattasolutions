<?php
require_once "../config.php";
require_once "../utils/callApi.class.php";

extract($_REQUEST);
//$idCustomer   = id
//$idVendor     = id
//listState     = []

$language = isset($_REQUEST["lang"]) ? $_REQUEST["lang"] : DEFAULT_LANG;
$perPage = (isset($filter["per_page"])) ? $filter["per_page"] : 100;


if (!isset($idCustomer) && !isset($idVendor)) {
	$response['status'] = StatusResponse::RES_BAD_REQUEST;
	$response['msg'][] = "idCustomer/idVendore required";
}


if ($response['status'] == ""){
	
	$listOrder = [];
	
	//--- customer ---
	if (isset($idCustomer)) {
		$url = SITE_URL . API_URL . "orders/?customer=" . $idCustomer . "&per_page=" . $perPage . "&" . CONSUMER_AUTH . "&lang=" . $language;
		$listOrder = CallAPI("GET", $url);
		$listOrder = json_decode($listOrder, true);
		
		if(isset($listState)) {
			
			$now = new DateTime();
			$now = $now->getTimestamp();
			$listOrderFilter = [];
			
			//parse start-end date
			foreach($listOrder as $key => $order) {
				foreach($order['line_items'] as $keyOrder => $itemOrder) {
					foreach($itemOrder['meta_data'] as $keyMetaData => $metaData) {
						if($metaData['key'] == "_ebs_start_format") {
							$listOrder[$key]['line_items'][$keyOrder]['start'] = (new DateTime($metaData['value']))->getTimestamp();
						} else if($metaData['key'] == "_ebs_end_format") {
							$listOrder[$key]['line_items'][$keyOrder]['end'] = (new DateTime($metaData['value']))->getTimestamp();
						}
					}
					
					if ($now>=$itemOrder['start'] && $now<=$itemOrder['end']) {
						$listOrder[$key]['line_items'][$keyOrder]['statusOrder'] = "running";
					} else if ($now<$itemOrder['start']) {
						$listOrder[$key]['line_items'][$keyOrder]['statusOrder'] = "pending";
					} else if ($now>$itemOrder['end']) {
						$listOrder[$key]['line_items'][$keyOrder]['statusOrder'] = "completed";
					}
					
				}
			}
			
			foreach($listOrder as $key => $order) {
				$rangeFound = false;
				foreach ($order['line_items'] as $keyOrder => $itemOrder) {
					if(in_array($itemOrder['statusOrder'], $listState)) {
						$rangeFound = true;
						break;
					}
				}
				if($rangeFound) {
					$listOrderFilter[] = $order;
				}
			}
			
			$listOrder = $listOrderFilter;
		}
		
		foreach($listOrder as $key => $order) {
			foreach ($order['line_items'] as $keyOrder => $itemOrder) {
				$url = SITE_URL . API_URL . "products/" . $listOrder[$key]['line_items'][$keyOrder]["product_id"] . "?customer=" . $idCustomer . "&per_page=" . $perPage . "&" . CONSUMER_AUTH . "&lang=" . $language;
				$order = CallAPI("GET", $url);
				$order = json_decode($order, true);
				$listOrder[$key]['line_items'][$keyOrder]['product'] = $order;
			}
		}
		
	} else {
		$url = SITE_URL . API_URL . "products/?per_page=" . $perPage . "&". CONSUMER_AUTH . "&lang=" . $language;
		$listProduct = CallAPI("GET", $url);
		$listProduct = json_decode($listProduct, true);
		
		$listProductUser = [];
		for($i=0; $i<count($listProduct); $i++){
			$product = $listProduct[$i];
			//p($product);
			//continue;
			for($j=0; $j<count($product["meta_data"]); $j++) {
				$metaData = $product["meta_data"][$j];
				if ($metaData['key'] == "eg-user" && $metaData["value"] == $idVendor && !in_array($product['id'], $listProductUser)) {
					$listProductUser[] = $product['id'];
				}
			}
		}
		if(count($listProductUser)>0) {
			
			for($i=0; $i<count($listProductUser); $i++) {
				$productId = $listProductUser[$i];
				$url = SITE_URL . API_URL . "orders/?product=" . $productId . "&per_page=" . $perPage . "&". CONSUMER_AUTH . "&lang=" . $language;
				$listOrderForVendor = CallAPI("GET", $url);
				$listOrderForVendor = json_decode($listOrderForVendor, true);
				
				foreach($listOrderForVendor as $key => $order) {
					$listOrder[$order['id']] = $order;
				}
			}
			
		}
	}
	
	if(empty($listOrder)) {
		$response['status'] = StatusResponse::RES_NO_RESULT;
		$response['msg'][] = "not exists order";
	} else {
		$response['status'] = StatusResponse::RES_OK;
		$response['msg'][] = "ok";
		$response['data'] = $listOrder;
	}
	
}

header('Content-Type: application/json');
echo json_encode($response);

?>

