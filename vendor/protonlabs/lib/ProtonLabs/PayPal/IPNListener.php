<?php
class ProtonLabs_PayPal_IPNListener {
	
	protected	$_messages;
	
	public function __construct() {
		
		$this->_messages	= array();
		
		$postData 		= file_get_contents('php://input');
		$ipnData		= array();
		$rawPostArray 	= explode('&', $postData);
		
		foreach ($rawPostArray as $keyValue) {
			$keyValue = explode ('=', $keyValue);
			if (count($keyValue) == 2) {
				$ipnData[$keyValue[0]] = urldecode($keyValue[1]);
			}
		}
		
		$this->newMessage( $ipnData );
	}
	
	public function newMessage( $ipnData ) {
		
		$message	= new ProtonLabs_PayPal_IPNListener_Message( $ipnData );
		$this->addMessage( $message );
		
	}
	
	public function addMessage( $message ) {
		$this->_messages[]	= $message;
	}
	
	public function getLastMessage() {
		if( count( $this->_messages ) ) {
			return $this->_messages[( count( $this->_messages ) - 1)];
		}
		return null;
	}
}