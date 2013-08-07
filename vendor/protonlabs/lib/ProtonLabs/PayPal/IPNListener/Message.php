<?php
class ProtonLabs_PayPal_IPNListener_Message {
	
	
	protected $_data;
	protected $_isValid;
	
	public function __construct( $data ) {
		$this->isValid	= null;
		$this->init( $data );
	}
	
	public function init( $data ) {
		$this->_data	= $data;
	}
	
	public function getData() {
		return $this->_data;
	}
	
	public function getTransactionId() {
		if(isset($this->_data['txn_id'])) {
			return $this->_data['txn_id'];
		} else if(isset($this->_data['transaction[0].id'])) {
			$idx = 0;
			do {
				$transId[] =  $this->_data["transaction[$idx].id"];
				$idx++;
			} while(isset($this->_data["transaction[$idx].id"]));
			return $transId;
		}
		return null;
	}
	
	public function setIsValid( $isValid ) {
		$this->_isValid	= $isValid;
	}
	
	public function isValid() {
		return $this->_isValid;
	}
	
	public function getTransactionType() {	
		if( isset( $this->_data['txn_type'] ) ) {
			return $this->_data['txn_type'];
		} elseif ( isset( $this->_data['transaction_type'] ) ) {
			return $this->_data['transaction_type'];
		}
		return null;
	}
	
	public function getBusiness() {
		if( isset( $this->_data['business'] ) ) {
			return $this->_data['business'];
		}
		return null;
	}
	
	public function getCharset() {
		if( isset( $this->_data['charset'] ) ) {
			return $this->_data['charset'];
		}
		return null;
	}
	
	public function getCustom() {
		if( isset( $this->_data['custom'] ) ) {
			return $this->_data['custom'];
		}
		return null;
	}
	
	public function getIPNTrackId() {
		if( isset( $this->_data['ipn_track_id'] ) ) {
			return $this->_data['ipn_track_id'];
		}
		return null;
	}
	
	public function getNotifyVersion() {
		if( isset( $this->_data['notify_version'] ) ) {
			return $this->_data['notify_version'];
		}
		return null;
	}
	
	public function getParentTransactionId() {
		if( isset( $this->_data['parent_txn_id'] ) ) {
			return $this->_data['parent_txn_id'];
		}
		return null;
	}
	
	public function getReceiptId() {
		if( isset( $this->_data['receipt_id'] ) ) {
			return $this->_data['receipt_id'];
		}
		return null;
	}
	
	public function getReceiverEmail() {
		if( isset( $this->_data['receiver_email'] ) ) {
			return $this->_data['receiver_email'];
		}
		return null;
	}
	
	public function getReceiverId() {
		if( isset( $this->_data['receiver_id'] ) ) {
			return $this->_data['receiver_id'];
		}
		return null;
	}
	
	public function getResend() {
		if( isset( $this->_data['resend'] ) ) {
			return $this->_data['resend'];
		}
		return null;
	}
	
	public function getResidenceCountry() {
		if( isset( $this->_data['residence_country'] ) ) {
			return $this->_data['residence_country'];
		}
		return null;
	}
	
	public function getTestIPN() {
		if( isset( $this->_data['test_ipn'] ) ) {
			return $this->_data['test_ipn'];
		}
		return null;
	}
	
	public function getVerifySign() {
		if( isset( $this->_data['verify_sign'] ) ) {
			return $this->_data['verify_sign'];
		}
		return null;
	}	
	
	public function getAddressCountry() {
		if( isset( $this->_data['address_country'] ) ) {
			return $this->_data['address_country'];
		}
		return null;
	}	
	
	public function getAddressCity() {
		if( isset( $this->_data['address_city'] ) ) {
			return $this->_data['address_city'];
		}
		return null;
	}
	
	public function getAddressCountryCode() {
		if( isset( $this->_data['address_country_code'] ) ) {
			return $this->_data['address_country_code'];
		}
		return null;
	}
	
	public function getAddressName() {
		if( isset( $this->_data['address_name'] ) ) {
			return $this->_data['address_name'];
		}
		return null;
	}
	
	public function getAddressState() {
		if( isset( $this->_data['address_state'] ) ) {
			return $this->_data['address_state'];
		}
		return null;
	}
	
	public function getAddressStatus() {
		if( isset( $this->_data['address_status'] ) ) {
			return $this->_data['address_status'];
		}
		return null;
	}
	
	public function getAddressStreet() {
		if( isset( $this->_data['address_street'] ) ) {
			return $this->_data['address_street'];
		}
		return null;
	}
	
	public function getAddressZip() {
		if( isset( $this->_data['address_zip'] ) ) {
			return $this->_data['address_zip'];
		}
		return null;
	}
	
	public function getContactPhone() {
		if( isset( $this->_data['contact_phone'] ) ) {
			return $this->_data['contact_phone'];
		}
		return null;
	}
	
	public function getFirstName() {
		if( isset( $this->_data['first_name'] ) ) {
			return $this->_data['first_name'];
		}
		return null;
	}
	
	public function getLastName() {
		if( isset( $this->_data['last_name'] ) ) {
			return $this->_data['last_name'];
		}
		return null;
	}
	
	public function getPayerBusinessName() {
		if( isset( $this->_data['payer_business_name'] ) ) {
			return $this->_data['payer_business_name'];
		}
		return null;
	}
	
	public function getPayerEmail() {
		if( isset( $this->_data['payer_email'] ) ) {
			return $this->_data['payer_email'];
		}
		return null;
	}
	
	public function getPayerId() {
		if( isset( $this->_data['payer_id'] ) ) {
			return $this->_data['payer_id'];
		}
		return null;
	}
	
	public function getAuthAmount() {
		if( isset( $this->_data['auth_amount'] ) ) {
			return $this->_data['auth_amount'];
		}
		return null;
	}
	
	public function getAuthExp() {
		if( isset( $this->_data['auth_exp'] ) ) {
			return $this->_data['auth_exp'];
		}
		return null;
	}
	
	public function getAuthId() {
		if( isset( $this->_data['auth_id'] ) ) {
			return $this->_data['auth_id'];
		}
		return null;
	}
	
	public function getAuthStatus() {
		if( isset( $this->_data['auth_status'] ) ) {
			return $this->_data['auth_status'];
		}
		return null;
	}
	
	public function getECheckTimeProcessed() {
		if( isset( $this->_data['echeck_time_processed'] ) ) {
			return $this->_data['echeck_time_processed'];
		}
		return null;
	}
	
	public function getExchangeRate() {
		if( isset( $this->_data['exchange_rate'] ) ) {
			return $this->_data['exchange_rate'];
		}
		return null;
	}
	
	public function getInvoice() {
		if( isset( $this->_data['invoice'] ) ) {
			return $this->_data['invoice'];
		}
		return null;
	}
	
	public function getMCCurrency() {
		if( isset( $this->_data['mc_currency'] ) ) {
			return $this->_data['mc_currency'];
		}
		return null;
	}
	
	public function getMCFee() {
		if( isset( $this->_data['mc_fee'] ) ) {
			return $this->_data['mc_fee'];
		}
		return null;
	}
	
	public function getMCGross() {
		if( isset( $this->_data['mc_gross'] ) ) {
			return $this->_data['mc_gross'];
		}
		return null;
	}
	
	public function getMCHandling() {
		if( isset( $this->_data['mc_handling'] ) ) {
			return $this->_data['mc_handling'];
		}
		return null;
	}
	
	public function getMCShipping() {
		if( isset( $this->_data['mc_shipping'] ) ) {
			return $this->_data['mc_shipping'];
		}
		return null;
	}
	
	public function getMemo() {
		if( isset( $this->_data['memo'] ) ) {
			return $this->_data['memo'];
		}
		return null;
	}
	
	public function getNumCartItems() {
		if( isset( $this->_data['num_cart_items'] ) ) {
			return $this->_data['num_cart_items'];
		}
		return null;
	}
	
	public function getPayerStatus() {
		if( isset( $this->_data['payer_status'] ) ) {
			return $this->_data['payer_status'];
		}
		return null;
	}
	
	public function getPaymentDate() {
		if( isset( $this->_data['payment_date'] ) ) {
			return $this->_data['payment_date'];
		}
		return null;
	}
	
	public function getPaymentFee() {
		if( isset( $this->_data['payment_fee'] ) ) {
			return $this->_data['payment_fee'];
		}
		return null;
	}
	
	public function getPaymentGross() {
		if( isset( $this->_data['payment_gross'] ) ) {
			return $this->_data['payment_gross'];
		}
		return null;
	}	
	
	public function getPaymentStatus() {
		if( isset( $this->_data['payment_status'] ) ) {
			return $this->_data['payment_status'];
		} elseif ( isset( $this->_data['status'] ) ) {
			return $this->_data['status'];
		}
		return null;
	}
	
	public function getPaymentType() {
		if( isset( $this->_data['payment_type'] ) ) {
			return $this->_data['payment_type'];
		}
		return null;
	}
	
	public function getPendingReason() {
		if( isset( $this->_data['pending_reason'] ) ) {
			return $this->_data['pending_reason'];
		}
		return null;
	}
	
	public function getProtectionEligibility() {
		if( isset( $this->_data['protection_eligibility'] ) ) {
			return $this->_data['protection_eligibility'];
		}
		return null;
	}
	
	public function getQuantity() {
		if( isset( $this->_data['quantity'] ) ) {
			return $this->_data['quantity'];
		}
		return null;
	}
	
	public function getReasonCode() {
		if( isset( $this->_data['reason_code'] ) ) {
			return $this->_data['reason_code'];
		}
		return null;
	}
	
	public function getRemainingSettle() {
		if( isset( $this->_data['remaining_settle'] ) ) {
			return $this->_data['remaining_settle'];
		}
		return null;
	}
	
	public function getSettleAmount() {
		if( isset( $this->_data['settle_amount'] ) ) {
			return $this->_data['settle_amount'];
		}
		return null;
	}
	
	public function getSettleCurrency() {
		if( isset( $this->_data['settle_currency'] ) ) {
			return $this->_data['settle_currency'];
		}
		return null;
	}
	
	public function getShipping() {
		if( isset( $this->_data['shipping'] ) ) {
			return $this->_data['shipping'];
		}
		return null;
	}
	
	public function getShippingMethod() {
		if( isset( $this->_data['shipping_method'] ) ) {
			return $this->_data['shipping_method'];
		}
		return null;
	}
	
	public function getTax() {
		if( isset( $this->_data['tax'] ) ) {
			return $this->_data['tax'];
		}
		return null;
	}
	
	public function getTransactionEntity() {
		if( isset( $this->_data['transaction_entity'] ) ) {
			return $this->_data['transaction_entity'];
		}
		return null;
	}
	
	public function getTrackingId() {
		if( isset( $this->_data['trackingId'] ) ) {
			return $this->_data['trackingId'];
		} elseif( isset( $this->_data['tracking_id'] ) ) {
			return $this->_data['tracking_id'];
		}
		return null;
	}
}





