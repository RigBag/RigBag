<?php
class ProtonLabs_PayPal {
	
   	/**
    * Last error message(s)
    * @var array
    */
   	protected $_errors = array();

   	/**
    * API Credentials
    * Use the correct credentials for the environment in use (Live / Sandbox)
    * @var array
    */
   	protected $_credentials = array(
    	'USER' 		=> '',
      	'PWD' 		=> '',
      	'SIGNATURE' => '',
   	);

   	/**
    * API endpoint
    * Live - https://api-3t.paypal.com/nvp
    * Sandbox - https://api-3t.sandbox.paypal.com/nvp
    * @var string
    */
   	protected $_endPoint = '';
   	
   	protected $_url		 = '';

   	/**
    * API Version
    * @var string
    */
   	protected $_version = '74.0';

   	protected $_token			= null;
  	
   	public function __construct( $user, $password, $signature ) {	
   		$this->setUser( $user );
   		$this->setPassword( $password );
   		$this->setSignature( $signature );
   	}  
   	
   	public function setUser( $user ) {
   		$this->_credentials['USER']	= $user;
   		return $this;
   	}
   	
   	public function setPassword( $password ) {
   		$this->_credentials['PWD']	= $password;
   		return $this;
   	}
   	
   	public function setSignature( $signature ) {
   		$this->_credentials['SIGNATURE']	= $signature;
   		return $this;
   	}
   	
   	public function setEndPoint( $endPoint ) {
   		$this->_endPoint	= $endPoint;
   		return $this;
   	}
   	
   	public function setPayPalUrl( $payPalUrl ) {
   		$this->_url			= $payPalUrl;
   		return $this;
   	}
   	
   	public function getPayPalUrl() {
   		return $this->_url;
   	}
   	
   	public function setToken( $token ) {
   		$this->_token		= $token;
   		return $this;
   	}
   	
   	public function getToken() {
   		return $this->_token;
   	}

   	public function hasErrors() {
   		if( count( $this->_errors ) ) {
   			return true;
   		}
   		return false;
   	}
   	
   	public function setExpressCheckout( $params ) {
   		$response = $this->request( 'setExpressCheckout', $params['requestParams'] + $params['orderParams'] + $params['item'] );
   		
   		
   		if( is_array( $response ) && $response['ACK'] == 'Success' ) {
   			$this->setToken( $response['TOKEN'] );
   		}
   		
   		return $response;
   	}
   	
   	public function getExpressCheckoutDetails() {
   		$checkoutDetails = $this->request( 'GetExpressCheckoutDetails', array('TOKEN' => $this->getToken() ) );
   		
   		return $checkoutDetails;
   	}
   	
   	public function doExpressCheckout( $params ) {
   		$params['TOKEN']	= $this->getToken();
   		$transactionId		= null;
   		$response 			= $this->request('DoExpressCheckoutPayment',$params);
   		 
   		if( is_array( $response ) && $response['ACK'] == 'Success' ) {
   			$transactionId = $response['PAYMENTINFO_0_TRANSACTIONID'];
   		}
   		return $transactionId;
   	}
   	
   	public function getPaymentUrl() {
   		return $this->getPayPalUrl() . '?cmd=_express-checkout&token=' . urlencode( $this->getToken() );
   	}
   	
   	/**
    * Make API request
    *
    * @param string $method string API method to request
    * @param array $params Additional request parameters
    * @return array / boolean Response array / boolean false on failure
    */
   	public function request( $method,$params = array() ) {
      	
   		$this->_errors = array();
      	if( empty($method) ) { //Check if API method is not empty
       		$this->_errors = array('API method is missing');
         	return false;
      	}

      	//Our request parameters
      	$requestParams = array(
        	'METHOD' => $method,
         	'VERSION' => $this->_version
      	) + $this->_credentials;

      	//Building our NVP string
      	$request = http_build_query($requestParams + $params);

      	//cURL settings
      	$curlOptions = array (
        	CURLOPT_URL 			=> $this->_endPoint,
         	CURLOPT_VERBOSE 		=> 1,
      		CURLOPT_SSL_VERIFYPEER 	=> false,
      		//CURLOPT_SSL_VERIFYPEER 	=> true,
         	CURLOPT_SSL_VERIFYHOST 	=> 2,
         	//CURLOPT_CAINFO 			=> dirname(__FILE__) . '/cacert.pem', //CA cert file
         	CURLOPT_RETURNTRANSFER 	=> 1,
         	CURLOPT_POST 			=> 1,
         	CURLOPT_POSTFIELDS 		=> $request
      	);

      	$ch = curl_init();
      	curl_setopt_array($ch,$curlOptions);

      	//Sending our request - $response will hold the API response
      	$response = curl_exec($ch);

      	//Checking for cURL errors
      	if (curl_errno($ch)) {
        	$this -> _errors = curl_error($ch);
         	curl_close($ch);
         	return false;
         	//Handle errors
      	} else  {
        	curl_close($ch);
         	$responseArray = array();
         	parse_str($response,$responseArray); // Break the NVP string to an array
         	return $responseArray;
      	}
   	}
}