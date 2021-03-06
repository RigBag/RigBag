<?php

namespace Proton\RigbagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proton\RigbagBundle\Entity\Advert
 */
class Advert
{
   
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $user_id
     */
    private $user_id;

    /**
     * @var integer $condition_id
     */
    private $condition_id;

    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $hash
     */
    private $hash;

    /**
     * @var string $location
     */
    private $location;

    /**
     * @var string $location_formated
     */
    private $location_formated;

    /**
     * @var float $location_lat
     */
    private $location_lat;

    /**
     * @var float $location_lng
     */
    private $location_lng;

    /**
     * @var string $swap_for
     */
    private $swap_for;

    /**
     * @var string $mode
     */
    private $mode;

    /**
     * @var string $price
     */
    private $price;

    /**
     * @var string $state
     */
    private $state;

    /**
     * @var string $currency
     */
    private $currency;

    /**
     * @var \DateTime $expired_at
     */
    private $expired_at;

    /**
     * @var \DateTime $created_at
     */
    private $created_at;

    /**
     * @var \DateTime $updated_at
     */
    private $updated_at;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $images;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $questions;

    /**
     * @var Proton\RigbagBundle\Entity\User
     */
    private $user;

    /**
     * @var Proton\RigbagBundle\Entity\DictionaryValue
     */
    private $condition;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $circles;

    
    /**
     * PROTON METHODS: start
     */
    
    public function getDescription( $type = '', $format = '' ) {
    	 
    	$description	= '';
    	 
    	switch( $type ) {
    		case 'tweet':
    			switch( $this->mode ) {
    				case 'sale':
    					$description .= 'FOR SALE: ';
    					$description .= $this->title . ' ';
    					$description .= $this->getCondition()->getName();
    					$description .= ', price: ' . $this->price . strtoupper( $this->currency );
    					$description .= ', ' . $this->getLocationDisplay() . '.';
    				break;
    				case 'swap':
    					$description .= 'SWAP: ';
    					$description .= $this->title . ' ';
    					$description .= $this->getCondition()->getName();
    					$description .= ' for ' . $this->getSwapFor();
    					$description .= ', ' . $this->getLocationDisplay() . '.';
    				break;
    				case 'freebie':
    					$description .= 'FREEBIE: ';
    					$description .= $this->title . ' ';
    					$description .= $this->getCondition()->getName();
    					$description .= ', ' . $this->getLocationDisplay() . '.';
    				break;
    			}
    		break;
    		case 'admin':
    			switch( $this->mode ) {
    				case 'sale':
    					$description .= $translator->trans('FOR SALE');
    					$description .= ': ';
    					$description .= $this->title . ' ';
    					$description .= $this->getCondition()->getName();
    					$description .= ', '.$translator->trans('price').': ' . $this->price . strtoupper( $this->currency );
    					$description .= ', ' . $this->getLocationDisplay() . '.';
    					break;
    				case 'swap':
    					$description .= $translator->trans('SWAP');
    					$description .= ': ';
    					$description .= $this->title . ' ';
    					$description .= $this->getCondition()->getName();
    					$description .= ' '.$translator->trans('for').' ' . $this->getSwapFor();
    					$description .= ', ' . $this->getLocationDisplay() . '.';
    					break;
    				case 'freebie':
    					$description .= $translator->trans('FREEBIE');
    					$description .= ': ';
    					$description .= $this->title . ' ';
    					$description .= $this->getCondition()->getName();
    					$description .= ', ' . $this->getLocationDisplay() . '.';
    					break;
    			}
    			break;
    		default:
    			$description .= $this->title;
    			if( $this->mode == 'swap' ) {
    				$description .= ' for ' . $this->swap_for;
    			}
    	}
    	 
    	return $description;
    	 
    }
    
    public function getAddedAgo() {
    	 
    	 
    	$date	= strtotime( $this->created_at->format('Y-m-d H:i:s') );
    	$now	= time();
    	$time	= $now - $date;
    	 
    	$days		= floor( $time / ( 60 * 60 * 24 ) );
    	$hours		= 0;
    	$minutes	= 0;
    	 
    	if( $days < 1 ) {
    
    		$rest	= $time - ( $days * 60 * 60 * 24 );
    
    		$hours	= floor( $rest / ( 60 * 60 ) );
    
    		if( $hours < 1 ) {
    			$rest	= $time - ( $days * 60 * 60 );
    			 
    			$minutes	= floor( $rest / 60 );
    		}
    	}
    	 
    	return	array(
    			'days'		=> $days,
    			'minutes'	=> $minutes,
    			'hours'		=> $hours
    	);
    }
    
    public function getMainImage() {
    	
    	$images	= $this->getImages();
    	$image	= null;
    	
    	foreach( $images as $img ) {
    		$image = $img;
    		if( $img->getIsMain() == 1 ) {
    			break;
    		}
    	}
    	return $image;
    }
    
    public function getLocationDisplay() {
    	 
    	$tmp		= array_reverse( explode( ',', $this->location ) );
    	$location	= '';
    	$lp			= 0;
    	foreach( $tmp as $t ) {
    		if( strlen( $location ) ) {
    			$location = ', ' . $location;
    		}
    		$location = $t . $location;
    		$lp++;
    		if( $lp == 2 ) {
    			break;
    		}
    	}
    	 
    	return trim( $location );
    }
    
    
    /**
     * PROTON METHODS: end
     */
    
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->circles = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user_id
     *
     * @param integer $userId
     * @return Advert
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;
    
        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set condition_id
     *
     * @param integer $conditionId
     * @return Advert
     */
    public function setConditionId($conditionId)
    {
        $this->condition_id = $conditionId;
    
        return $this;
    }

    /**
     * Get condition_id
     *
     * @return integer 
     */
    public function getConditionId()
    {
        return $this->condition_id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Advert
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Advert
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    
        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
    	if( !$this->hash ) {
    		$uId		= $this->user->getId();
    		$this->hash	= $uId . substr( time(), strlen( $uId ) );
    	}
        return $this->hash;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Advert
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set location_formated
     *
     * @param string $locationFormated
     * @return Advert
     */
    public function setLocationFormated($locationFormated)
    {
        $this->location_formated = $locationFormated;
    
        return $this;
    }

    /**
     * Get location_formated
     *
     * @return string 
     */
    public function getLocationFormated()
    {
        return $this->location_formated;
    }

    /**
     * Set location_lat
     *
     * @param float $locationLat
     * @return Advert
     */
    public function setLocationLat($locationLat)
    {
        $this->location_lat = $locationLat;
    
        return $this;
    }

    /**
     * Get location_lat
     *
     * @return float 
     */
    public function getLocationLat()
    {
        return $this->location_lat;
    }

    /**
     * Set location_lng
     *
     * @param float $locationLng
     * @return Advert
     */
    public function setLocationLng($locationLng)
    {
        $this->location_lng = $locationLng;
    
        return $this;
    }

    /**
     * Get location_lng
     *
     * @return float 
     */
    public function getLocationLng()
    {
        return $this->location_lng;
    }

    /**
     * Set swap_for
     *
     * @param string $swapFor
     * @return Advert
     */
    public function setSwapFor($swapFor)
    {
        $this->swap_for = $swapFor;
    
        return $this;
    }

    /**
     * Get swap_for
     *
     * @return string 
     */
    public function getSwapFor()
    {
        return $this->swap_for;
    }

    /**
     * Set mode
     *
     * @param string $mode
     * @return Advert
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    
        return $this;
    }

    /**
     * Get mode
     *
     * @return string 
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Advert
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return Advert
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Advert
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    
        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set expired_at
     *
     * @param \DateTime $expiredAt
     * @return Advert
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expired_at = $expiredAt;
    
        return $this;
    }

    /**
     * Get expired_at
     *
     * @return \DateTime 
     */
    public function getExpiredAt()
    {
        return $this->expired_at;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Advert
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Advert
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Add images
     *
     * @param Proton\RigbagBundle\Entity\AdvertImage $images
     * @return Advert
     */
    public function addImage(\Proton\RigbagBundle\Entity\AdvertImage $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param Proton\RigbagBundle\Entity\AdvertImage $images
     */
    public function removeImage(\Proton\RigbagBundle\Entity\AdvertImage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add questions
     *
     * @param Proton\RigbagBundle\Entity\QaPosition $questions
     * @return Advert
     */
    public function addQuestion(\Proton\RigbagBundle\Entity\QaPosition $questions)
    {
        $this->questions[] = $questions;
    
        return $this;
    }

    /**
     * Remove questions
     *
     * @param Proton\RigbagBundle\Entity\QaPosition $questions
     */
    public function removeQuestion(\Proton\RigbagBundle\Entity\QaPosition $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set user
     *
     * @param Proton\RigbagBundle\Entity\User $user
     * @return Advert
     */
    public function setUser(\Proton\RigbagBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return Proton\RigbagBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set condition
     *
     * @param Proton\RigbagBundle\Entity\DictionaryValue $condition
     * @return Advert
     */
    public function setCondition(\Proton\RigbagBundle\Entity\DictionaryValue $condition = null)
    {
        $this->condition = $condition;
    
        return $this;
    }

    /**
     * Get condition
     *
     * @return Proton\RigbagBundle\Entity\DictionaryValue 
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Add circles
     *
     * @param Proton\RigbagBundle\Entity\Circle $circles
     * @return Advert
     */
    public function addCircle(\Proton\RigbagBundle\Entity\Circle $circles)
    {
        $this->circles[] = $circles;
    
        return $this;
    }

    /**
     * Remove circles
     *
     * @param Proton\RigbagBundle\Entity\Circle $circles
     */
    public function removeCircle(\Proton\RigbagBundle\Entity\Circle $circles)
    {
        $this->circles->removeElement($circles);
    }

    /**
     * Get circles
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCircles()
    {
        return $this->circles;
    }
    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
    	$this->created_at	= new \DateTime();
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setUpdatedAtValue()
    {
    	$this->updated_at	= new \DateTime();
    }
    /**
     * @var string $paypal_id
     */
    private $paypal_id;


    /**
     * Set paypal_id
     *
     * @param string $paypalId
     * @return Advert
     */
    public function setPaypalId($paypalId)
    {
        $this->paypal_id = $paypalId;
    
        return $this;
    }

    /**
     * Get paypal_id
     *
     * @return string 
     */
    public function getPaypalId()
    {
        return $this->paypal_id;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $facebookObjects;


    /**
     * Add facebookObjects
     *
     * @param Proton\RigbagBundle\Entity\FacebookObject $facebookObjects
     * @return Advert
     */
    public function addFacebookObject(\Proton\RigbagBundle\Entity\FacebookObject $facebookObjects)
    {
        $this->facebookObjects[] = $facebookObjects;
    
        return $this;
    }

    /**
     * Remove facebookObjects
     *
     * @param Proton\RigbagBundle\Entity\FacebookObject $facebookObjects
     */
    public function removeFacebookObject(\Proton\RigbagBundle\Entity\FacebookObject $facebookObjects)
    {
        $this->facebookObjects->removeElement($facebookObjects);
    }

    /**
     * Get facebookObjects
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFacebookObjects()
    {
        return $this->facebookObjects;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $facebookActions;


    /**
     * Add facebookActions
     *
     * @param Proton\RigbagBundle\Entity\FacebookAction $facebookActions
     * @return Advert
     */
    public function addFacebookAction(\Proton\RigbagBundle\Entity\FacebookAction $facebookActions)
    {
        $this->facebookActions[] = $facebookActions;
    
        return $this;
    }

    /**
     * Remove facebookActions
     *
     * @param Proton\RigbagBundle\Entity\FacebookAction $facebookActions
     */
    public function removeFacebookAction(\Proton\RigbagBundle\Entity\FacebookAction $facebookActions)
    {
        $this->facebookActions->removeElement($facebookActions);
    }

    /**
     * Get facebookActions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFacebookActions()
    {
        return $this->facebookActions;
    }
}