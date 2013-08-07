<?php
class ProtonLabs_Facebook_Service {
	
	protected $_ogUrl;
	protected $_accessToken;
	protected $_noFeedStory;
	protected $_appId;
	protected $_appSecret;
	protected $_scope;
	
	public function __construct( $appId = null, $appSecret = null, $scope = null ) {
		$this->setAppId( $appId )
				->setAppSecret( $appSecret )
				->setScope( $scope );
	}
	
	public function setScope( $scope ) {
		$this->_scope	= $scope;
		return $this;
	}
	
	public function setAppId( $appId ) {
		$this->_appId	= $appId;
		return $this;
	}
	
	public function setAppSecret( $appSecret ) {
		$this->_appSecret	= $appSecret;
		return $this;
	}
	
	public function generateAuthDialogUrl( $backUrl, $state ) {
		
		$dialogUrl 	= 'https://www.facebook.com/dialog/oauth?client_id=' .
					$this->_appId . '&redirect_uri=' . urlencode( $backUrl ) . 
					'&state=' . $state . '&scope=' . $this->_scope;
		
		return $dialogUrl;
	}
	
	public function checkAuthState( $sessionState, $requestState ) {
		
		if( !is_null( $sessionState ) && !is_null( $requestState ) && ( $sessionState === $requestState ) ) {
			return true;
		}
		return false;
	}
	
	public function readAccessToken( $backUrl, $code ) {
		
		$tokenUrl	= $this->generateAccessTokenUrl( $backUrl, $code );
		$response 	= file_get_contents( $tokenUrl );
		$params 	= null;
		parse_str($response, $params);
		
		$this->setAccessToken( $params['access_token'] );
		
		return $params['access_token'];
	}
	
	public function generateAccessTokenUrl( $backUrl, $code ) {
		
		$tokenUrl = 'https://graph.facebook.com/oauth/access_token?' .
					'client_id=' . $this->_appId . '&redirect_uri=' . urlencode( $backUrl ) .
					'&client_secret=' . $this->_appSecret . '&code=' . $code;
		
		return $tokenUrl;
	}
	
	public function generateUserProfilePictureUrl( $userId, $pictureSize = 'large' ) {
		return 'https://graph.facebook.com/' . $userId . '/picture?type=' . $pictureSize . '&access_token=' . $this->getAccessToken();
	}
	
	public function readUserData( $userId ) {
		
		$graphUrl = 'https://graph.facebook.com/' . $userId . '?access_token=' . $this->getAccessToken();
		
		return json_decode( file_get_contents( $graphUrl ) );
	}
	
	public function setNoFeedStory( $noFeedStory ) {
		if( $noFeedStory ) {
			$this->_noFeedStory	= true;
		} else {
			$this->_noFeedStory	= false;
		}
		return $this;
	}
	
	public function getNoFeedStory() {
		if( !$this->_noFeedStory ) {
			return false;
		}
		return true;
	}
	
	public function setOGUrl( $ogUrl ) {
		$this->_ogUrl	= $ogUrl;
		return $this;
	}
	
	public function getOGUrl() {
		return $this->_ogUrl;
	}
	
	public function setAccessToken( $accessToken ) {
		$this->_accessToken	= $accessToken;
		return $this;
	}
	
	public function getAccessToken() {
		return $this->_accessToken;
	}
	
	public function getAllActions( $userId, $action ) {
		$url	= $this->prepareUrl( $userId, $action ) . '?access_token=' . $this->getAccessToken();
		return json_decode( file_get_contents( $url ) );
	}
	
	public function createAction( $userId, $action, $objects ) {
		
		$url	= 'https://graph.facebook.com/' . $userId . '/' . $action;
		
		$fields	= array(
						'access_token' 	=> $this->getAccessToken(),
						'no_feed_story' => $this->getNoFeedStory()
					);
		
		foreach( $objects as $k => $v ) {
			$fields[$k]	= $v;
		}
		
		$fields	= array_merge( $fields, $objects );
		
		$ch = curl_init( $url );
		
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER 	=> true,
			CURLOPT_POST 			=> true,
			CURLOPT_POSTFIELDS 		=> $fields
		));
		
		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}
	
	public function deleteAction( $objectId ) {
		
	}
	
	protected function prepareUrl( $userId, $action ) {
		
		$url	= $this->getOGUrl();
		$url	= str_replace( '%user%', $userId, $url );
		$url	= str_replace( '%action%', $action, $url );
		
		return $url;
	}
	
	public function auth() {
		
	}
}