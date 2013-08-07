<?php

$path = __DIR__.'/src/Adaptive/lib/';

set_include_path(get_include_path() . PATH_SEPARATOR . $path);


include_once('services/AdaptivePayments/AdaptivePaymentsService.php');
include_once('PPLoggingManager.php');


class PayPal_AdaptivePayment {
	
}

class PayPal_AdaptivePayment_PPLoggingManager extends PPLoggingManager {
	
}


class PayPal_IPNMessage {
	
	protected $ipnData;
	
	public function __construct() {
		
		$postData = file_get_contents('php://input');
		
		$rawPostArray = explode('&', $postData);
		foreach ($rawPostArray as $keyValue) {
			$keyValue = explode ('=', $keyValue);
			if (count($keyValue) == 2) {
				$this->ipnData[$keyValue[0]] = urldecode($keyValue[1]);
			}
		}
	}
	
	public function getIPNData() {
		return $this->ipnData;
	}
	
	public function getTransactionType() {
		return $this->ipnData['transaction_type'];
	}
	
	public function getTransactionId() {
		if(isset($this->ipnData['txn_id'])) {
			return $this->ipnData['txn_id'];
		} else if(isset($this->ipnData['transaction[0].id'])) {
			$idx = 0;
			do {
				$transId[] =  $this->ipnData["transaction[$idx].id"];
				$idx++;
			} while(isset($this->ipnData["transaction[$idx].id"]));
			return $transId;
		}
	}
}