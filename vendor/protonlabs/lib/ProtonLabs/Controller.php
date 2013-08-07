<?php
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Proton\RigbagBundle\Entity\QaPosition;
use WindowsAzure\Common\ServicesBuilder;
use WindowsAzure\Common\ServiceException;
use WindowsAzure\Common\Blob;
use WindowsAzure\Blob\Models\CreateBlobOptions;

class ProtonLabs_Controller extends Controller {
	
	public function setupLocale($request) {
		if(isset($_GET['setlocale'])) {
			$this->get('session')->set('_locale', $_GET['setlocale']);
		}
		$request->setLocale($this->get('session')->get('_locale'));
	}
	
	public function isLoged() {
		
		return $this->getRequest()->getSession()->get( 'isLoged', false );
		
	}
	
	public function isTablet() {
		
		$dd		= new \MobileDetect_Main();
		return $dd->isTablet();
	}
	
	public function auth( $full = true ) {
		if( $this->isLoged() || ( $this->getUserId() && !$full ) ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getUserId() {
		
		if( $this->isLoged() ) {
			$ud	= $this->getRequest()->getSession()->get( 'userAuthData' );
			
			if( isset( $ud['id'] ) ) {
				return $ud['id'];
			}
		}
		return $this->get('session')->get('userId', null);
	}
	
	public function loginUser( $data ) {
		
		$this->getRequest()->getSession()->set( 'userAuthData', $data );
		$this->getRequest()->getSession()->set( 'isLoged', true );
		
	}
	
	public function logoutUser() {
		
		$this->getRequest()->getSession()->set( 'userAuthData', null );
		$this->getRequest()->getSession()->set( 'isLoged', false );
		$this->getRequest()->getSession()->set( 'userId', null );
		$this->getRequest()->getSession()->set( 'gpToken', null );
		$this->getRequest()->getSession()->set( 'twOAuth', null );
		$this->getRequest()->getSession()->set( 'twToken', null );
		
	}
	
	public function getHost( $ssl = false ) {
		
		$host	= ( $ssl ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'];
		
		return $host;
	}
	
	public function unAuthResponse() {
		$response	= new Response();
		$response->setStatusCode( '403', 'Upsssss. You haven\'t access here.' );
		return $response;
	}
	
	public function blackholeResponse() {
		
		$response	= new Response();
		
		$response->headers->set( 'Location', $this->getHost() . $this->generateUrl( 'start' ) );
		$response->setStatusCode( 302 );
		
		return $response;
	}
	
	public function jsonResponse( $data, $isAPI = false, $responseCode = 200 ) {
		
		$response	= new Response();
		$response->setStatusCode( $responseCode );
		$response->headers->set( 'Content-type', 'application/json; charset=utf-8' );
		
		if( !isset( $data['actionStamp'] ) ) {
			$data['actionStamp'] = '';
		}
		
		if( $isAPI ) {
			return $this->render( 'ProtonRigbagBundle:Extras:default-api.json.twig', array( 'result' => $data ), $response );
		} else {
			return $this->render( 'ProtonRigbagBundle:Extras:default.json.twig', array( 'result' => $data ), $response );
		}
	}
	
	public function send($type, $data) {
		$em 	= $this->getDoctrine()->getManager();
		$template = 'mail-default';
		$topic = 'Message from RigBag';
		$viewParams = array();

		switch($type) {
			case 'advertAsk':
				$template = 'advert-ask';
				$topic = 'Advert question from RigBag';
				$advert		= $em->getRepository( 'ProtonRigbagBundle:Advert' )->findOneBy( array( 'id' => $data['advertId']) );
				$advertUrl = 'https://' . $this->getRequest()->getHost() . $this->generateUrl( 'start' ) . '#/adverts/view/' . $advert->getHash() . '/';
				$question 	= $em->getRepository( 'ProtonRigbagBundle:QaPosition' )->find( $data['qaId'] );
				$toUser = $em->getRepository( 'ProtonRigbagBundle:User' )->find( $question->getToUserId() );
				$user = $em->getRepository( 'ProtonRigbagBundle:User' )->find( $question->getUserId() );
				$viewParams = array('user'=>$user, 'toUser'=>$toUser, 'advertUrl'=>$advertUrl, 'body' => $question->getBody());
				$emails = $toUser->getEmail();
				break;
			case 'freeOffer':
				$template = 'free-offer';
				$topic = 'Offer from RigBag';
				$advert		= $em->getRepository( 'ProtonRigbagBundle:Advert' )->findOneBy( array( 'id' => $data['advertId']) );
				$advertUrl = 'https://' . $this->getRequest()->getHost() . $this->generateUrl( 'start' ) . '#/adverts/view/' . $advert->getHash() . '/';
				$question 	= $em->getRepository( 'ProtonRigbagBundle:QaPosition' )->find( $data['qaId'] );
				$toUser = $em->getRepository( 'ProtonRigbagBundle:User' )->find( $question->getToUserId() );
				$user = $em->getRepository( 'ProtonRigbagBundle:User' )->find( $question->getUserId() );
				$viewParams = array('user'=>$user, 'toUser'=>$toUser, 'advertUrl'=>$advertUrl, 'body' => $question->getBody());
				$emails = $toUser->getEmail();
				break;
			case 'swapOffer':
				$template = 'swap-offer';
				$topic = 'Offer from RigBag';
				$advert		= $em->getRepository( 'ProtonRigbagBundle:Advert' )->findOneBy( array( 'id' => $data['advertId']) );
				$advertUrl = 'https://' . $this->getRequest()->getHost() . $this->generateUrl( 'start' ) . '#/adverts/view/' . $advert->getHash() . '/';
				$question 	= $em->getRepository( 'ProtonRigbagBundle:QaPosition' )->find( $data['qaId'] );
				$toUser = $em->getRepository( 'ProtonRigbagBundle:User' )->find( $question->getToUserId() );
				$user = $em->getRepository( 'ProtonRigbagBundle:User' )->find( $question->getUserId() );
				$viewParams = array('user'=>$user, 'toUser'=>$toUser, 'advertUrl'=>$advertUrl, 'body' => $question->getBody());
				$emails = $toUser->getEmail();
				break;
			case 'suggestAdvert':
				$template = 'suggest';
				$topic = 'Suggestion from RigBag';
				$user	= $em->getRepository( 'ProtonRigbagBundle:User' )->find( $data['userId'] );
				$advert		= $em->getRepository( 'ProtonRigbagBundle:Advert' )->findOneBy( array( 'id' => $data['advertId']) );
				$advertUrl = 'https://' . $this->getRequest()->getHost() . $this->generateUrl( 'start' ) . '#/adverts/view/' . $advert->getHash() . '/';
				$viewParams = array('user'=>$user,'advertUrl'=>$advertUrl,'message'=>$data['msg']);
				$emails = $data['emails'];
				break;
		}
		if (!isset($emails)) {
			return false;
		}
		$twig = 'ProtonRigbagBundle:Mail:%s.html.twig';
		$cid = 'https://' . $this->getRequest()->getHost() . '/bundles/protonrigbag/images/rb-logo.png';
		$viewParams['cid'] = $cid;
		$twig = sprintf($twig, $template);
		$message = \Swift_Message::newInstance()
	       ->setSubject($topic)
	       ->setFrom('hello@rigbag.com')
	       ->setTo($emails)
	       ->setContentType("text/html")
	       ->setBody(
	         	$this->renderView($twig, $viewParams)
           	)
		;
		return $this->get('mailer')->send($message);
	}
	
	public function systemMessage($type, $data) {
		
		$message = null;
		switch($type){
			case 'swapRejected':
				$message = 'Your swap offer has just been rejected.';
				break;
			case 'freeRejected':
				$message = 'Your free take offer has just been rejected.';
				break;
			case 'swapAccepted':
				$message = 'Your swap offer has just been accepted.';
				break;
			case 'freeAccepted':
				$message = 'Your free take offer has just been accepted.';
				break;
		}
		
		if($message == null) {
			return;
		}
		$advert = $data['advert'];
		$toUser = $data['toUser'];
		
		$em 	= $this->getDoctrine()->getManager();
		$qa		= new QaPosition();
		$qa->setUser( $advert->getUser() )
			->setAdvert( $advert )
			->setToUser( $toUser )
			->setBody( $message )
			->setState( 'system' )
			->setAnswersNum( 0 );
		$em->persist( $qa );
		$em->flush();
	}
	
	public function suggestAdvertToFriend($advertId, $msg, $emails, $userId) {
		return $this->send('suggestAdvert', array('advertId' => $advertId, 'msg' => $msg, 'emails' => $emails, 'userId' => $userId));
	} 
	
	public function msBlobFileExist($url) {
		//$this->container->get('logger')->info('file exists', array($url));
		$url = @parse_url($url); 
		if (!$url) return false; 
		 
		$url = array_map('trim', $url); 
		$url['port'] = (!isset($url['port'])) ? 80 : (int)$url['port']; 
		 
		$path = (isset($url['path'])) ? $url['path'] : '/'; 
		$path .= (isset($url['query'])) ? "?$url[query]" : ''; 
		 
		if (isset($url['host']) && $url['host'] != gethostbyname($url['host'])) { 
		 
		     $fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30); 
		 
		      if (!$fp) return false; //socket not opened
		 
		        fputs($fp, "HEAD $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n"); //socket opened
		        $headers = fread($fp, 4096); 
		        fclose($fp); 
		 
			 if(preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers)){//matching header
			       return true; 
			 } 
			 else return false;
		 
		 } // if parse url
		 else return false;
	}
	
	public function msBlobImageResize($imgPath, $dimensions) {
		$mediaUrl = $this->container->getParameter('azure.storage.url');
		$newWidth = $dimensions['w'];
		$newHeight = $dimensions['h'];
		$origUrl = $mediaUrl . str_replace('%size%', 'org', $imgPath);
		$fPath = str_replace( '%size%', $newWidth . 'x' . $newHeight, $imgPath );
		
		$confAzure			= $this->container->getParameter( 'azure' );
		$connectionString	= 'DefaultEndpointsProtocol=' . $confAzure['storage']['protocol'] . ';AccountName=' . $confAzure['storage']['account'] . ';AccountKey=' . $confAzure['storage']['primaryKey'];
		$blobProxy			= ServicesBuilder::getInstance()->createBlobService( $connectionString );
		
		$fileContentSrc = @file_get_contents($origUrl);
		if(!$fileContentSrc) {
			return;
		}
		$image = \PhpThumb_Factory::create( $fileContentSrc, array( 'jpegQuality' => 75, 'adaptiveResize' =>false), true );
		$image->setFormat( 'JPG' );
		$fileContent = $image->getImageAsString();
		$image = \PhpThumb_Factory::create( $fileContentSrc, array( 'jpegQuality' => 75, 'resizeUp' => true ), true );
		$image->adaptiveResize( $newWidth, $newHeight );
		
		$blobOptions	= new CreateBlobOptions();
		$blobOptions->setContentType( 'image/jpeg' );
		$blobProxy->createBlockBlob( $confAzure['storage']['container'], $fPath, $image->getImageAsString(), $blobOptions );
	}
}