<?php
class ProtonLabs_Facebook_OGP_Object {
	
	
	protected $_appId;
	protected $_namespaces;
	protected $_type;
	protected $_locale;
	protected $_siteName;	
	protected $_title;
	protected $_images;
	protected $_url;
	protected $_description;
	protected $_others;
	
	
	public function __construct() {
		$this->init();	
	}
	
	public function init() {
		$this->_images		= array();
		$this->_namespaces	= array();
		$this->_others		= array();
	}
	
	public function setAppId( $appId ) {
		$this->_appId		= $appId;
		return $this;
	}
	
	public function setOthers( $others ) {
		$this->_others	= $others;
		return $this;
	}
	
	public function addOther( $other ) {
		foreach( $other as $k => $v ) {
			$this->_others[$k] = $v;
		}
		return $this;
	}
	
	public function setNamespaces( $namespaces ) {
		$this->_namespaces	= $namespaces;
		return $this;
	}
	
	public function addNamespace( $namespace ) {
		$this->_namespaces[]	= $namespace;
		return $this;
	}
	
	public function setType( $type ) {
		$this->_type		= $type;
		return $this;
	}
	
	public function setLocale( $locale ) {
		$this->_locale		= $locale;
		return $this;
	}
	
	public function setSiteName( $siteName ) {
		$this->_siteName	= $siteName;
		return $this;
	}
	
	public function setTitle( $title ) {
		$this->_title		= $title;
		return $this;
	}
	
	public function setImages( $images ) {
		$this->_images		= $images;
		return $this;
	}
	
	public function addImage( $image ) {
		$this->_images[]	= $image;
	}
	
	public function setUrl( $url ) {
		$this->_url			= $url;
		return $this;
	}
	
	public function setDescription( $description ) {
		$this->_description	= $description;
		return $this;
	}
	
	public function getAppId() {
		return $this->_appId;
	}
	
	public function getOthers() {
		return $this->_others;
	}
	
	public function getNamespaces() {
		return $this->_namespaces;
	}
	
	public function getSiteName() {
		return $this->_siteName;
	}
	
	public function getLocale() {
		return $this->_locale;
	}
	
	public function getType() {
		return $this->_type;
	}
	
	public function getTitle() {
		return $this->_title;
	}
	
	public function getImages() {
		return $this->_images;
	}
	
	public function getUrl() {
		return $this->_url;
	}
	
	public function getDescription() {
		return $this->_description;
	}
	
	public function renderHeadPrefix() {
		$prefix	= 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#';
		
		foreach( $this->_namespaces as $namespaceKey => $namespaceUrl ) {
			$prefix .= ' ' . $namespaceUrl;
		}
		
		return $prefix;
	}
	
	public function generateMetaTags() {
		
		$metaTags		= array();
		
		$metaTags['fb:app_id']			= $this->getAppId();
		$metaTags['og:type']			= $this->getType();
		$metaTags['og:site_name']		= $this->getSiteName();
		$metaTags['og:locale']			= $this->getLocale();
		$metaTags['og:title']			= $this->getTitle();
		$metaTags['og:description']		= $this->getDescription();
		$metaTags['og:url']				= $this->getUrl();
		
		$images							= $this->getImages();
		$metaTags['og:images']			= array();
		
		foreach( $images as $image ) {
			$metaTags['og:images'][]			= $image;
		}
		
		foreach( $this->_others as $otherKey => $otherValue ) {
			$metaTags[$otherKey]		= $otherValue;
		}
		
		return $metaTags;
	}
	
	public function renderMetaTags() {
		
		$metaTagsData		= $this->generateMetaTags();
		$metaTagsString		= '';
		
		foreach( $metaTagsData as $mtKey => $mtValue ) {
			if( $mtKey == 'og:images' ) {
				
				foreach( $mtValue as $mtValueImg ) {

					if( isset( $mtValueImg['url'] ) ) {
						$metaTagsString .= '<meta property="og:image" content="' . $mtValueImg['url'] . '"/>' . "\n";
						if( isset( $mtValueImg['width'] ) ) {
							$metaTagsString .= '<meta property="og:image:width" content="' . $mtValueImg['width'] . '"/>' . "\n";
						}
						if( isset( $mtValueImg['height'] ) ) {
							$metaTagsString .= '<meta property="og:image:height" content="' . $mtValueImg['height'] . '"/>' . "\n";
						}
						if( isset( $mtValueImg['type'] ) ) {
							$metaTagsString .= '<meta property="og:image:type" content="' . $mtValueImg['type'] . '"/>' . "\n";
						}
					}
				}	
			} 
			else {
				$metaTagsString .= '<meta property="' . $mtKey . '" content="' . $mtValue . '"/>' . "\n";
			}
		}
		
		return $metaTagsString;
	}
	
}


