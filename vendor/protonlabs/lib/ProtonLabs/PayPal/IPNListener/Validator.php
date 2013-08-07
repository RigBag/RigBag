<?php
class ProtonLabs_PayPal_IPNListener_Validator {

	protected $_url;
	
	
	public function __construct( $url = '' ) {
		$this->setUrl( $url );
	}
	
	public function setUrl( $url ) {
		$this->_url	= $url;
	}
	
	public function getUrl() {
		return $this->_url;
	}
	
	public function verify( $message ) {
		
		$req	= 'cmd=_notify-validate';
		$data	= $message->getData();
		
		if(function_exists('get_magic_quotes_gpc')) {
			$get_magic_quotes_exists = true;
		}
		foreach ($data as $key => $value) {
			if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
				$value = urlencode(stripslashes($value));
			} else {
				$value = urlencode($value);
			}
			$req .= "&$key=$value";
		}
		
		
		$ch = curl_init( $this->getUrl() );
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req );
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
		
		if( !($res = curl_exec($ch)) ) {
			// error_log("Got " . curl_error($ch) . " when processing IPN data");
			curl_close($ch);
			exit;
		}
		curl_close($ch);
		
		if (strcmp ($res, "VERIFIED") == 0) {
			$message->setIsValid( true );
		} else if (strcmp ($res, "INVALID") == 0) {
			$message->setIsValid( true );
		}
		
	}
}

