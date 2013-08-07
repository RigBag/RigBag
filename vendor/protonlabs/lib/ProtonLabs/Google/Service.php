<?php
class ProtonLabs_Google_Service {
	
	protected $_client;
	protected $_oauth;
	protected $_accessToken;
	
	
	public function __construct( $clientId = null, $clientSecret = null, $developerKey = null, $appName = null ) {
		
		$this->setClient( new \GoogleApi_Main() );
				
		$this->getClient()->setApplicationName( $appName );
		$this->getClient()->setClientId( $clientId );
		$this->getClient()->setClientSecret( $clientSecret );
		$this->getClient()->setDeveloperKey( $developerKey );
		
	}
	
	public function initOAuth() {
		$this->_oauth	= new \GoogleApi_Oauth2Service( $this->getClient() ); 
	}
	
	public function setBackUrl( $backUrl ) {
		$this->getClient()->setRedirectUri( $backUrl );
		return $this;
	}
	
	public function getUserInfo() {
		return $this->_oauth->userinfo->get();
	}
	
	public function createAuthUrl() {
		$authUrl 	= $this->_client->createAuthUrl();
		$authUrl	= str_replace( 'index.php/', '', $authUrl );
		return $authUrl;
	}
	
	public function authenticate( $code ) {
		$this->getClient()->authenticate( $code );
	}
	
	public function getClient() {
		return $this->_client;
	}
	
	public function getOAuth() {
		return $this->_oauth;
	}
	
	public function setClient( $client ) {
		$this->_client	= $client;
		return $this;
	}
	
	public function setOAuth( $oauth ) {
		$this->_oauth	= $oauth;
		return $this;
	}
	
	public function setAccessToken( $accessToken ) {
		$this->_accessToken	= $accessToken;
		$this->_client->setAccessToken( $accessToken );
		return $this;
	}
	
	public function getAccessToken() {
		if( !$this->_accessToken ) {
			$this->_accessToken = $this->_client->getAccessToken();
		}
		return $this->_accessToken;
	}
}