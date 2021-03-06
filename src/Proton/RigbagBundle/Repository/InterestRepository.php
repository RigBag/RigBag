<?php

namespace Proton\RigbagBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * InterestRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InterestRepository extends EntityRepository
{
	public function findAll() {
	
		$query = $this->createQueryBuilder('e')
								->orderBy('e.name', 'ASC')
								->getQuery();
						
		return $query->getResult();
		
	}
	
	public function search( $q, $missCircles = array(), $params = array() ) {
	
		extract( $params );
		
		if( strlen( $q ) || ( isset( $force ) && $force ) ) {
	
			$qb		= $this->createQueryBuilder('c');
				
			$query = $qb->where( '(' . $qb->expr()->like('c.name', ':q') . ')' );
				
			$query->setParameter( 'q', '%' . $q . '%' );
			$query->addOrderBy('c.name', 'ASC');
	
			if( isset( $offset ) ) {
				$query->setFirstResult( $offset );
			}
			if( isset( $limit ) ) {
				$query->setMaxResults( $limit );
			}
			
			$result = $query->getQuery()->getResult();
				
			return $result;
	
		}
		return array();
	}
	
	public function findForCircles( $circlesIds ) {
		
		if( $circlesIds ) {
			
			$query	= $this->createQueryBuilder('i')->join( 'i.circles', 'c' );
	
			$query->where( 'c.id IN (:circlesIds)' );
			$query->setParameter( 'circlesIds', implode( ',', $circlesIds ) );
			$query->groupBy( 'i.id' );
				
			// EXECUTE
			$query->addOrderBy('i.name', 'ASC');
			return $query->getQuery()->getResult();
		}
		
		return array();
	}
}
