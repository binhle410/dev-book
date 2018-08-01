<?php

namespace Magenta\Bundle\CBookModelBundle\Repository\User;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UserRepository extends ServiceEntityRepository {
	public function __construct(RegistryInterface $registry) {
		parent::__construct($registry, User::class);
	}
	
	public function findOneByOrganisationCodeNric($code, $nric) {
		// automatically knows to select Products
		// the "p" is an alias you'll use in the rest of the query
		$qb   = $this->createQueryBuilder('u');
		$expr = $qb->expr();
		$qb
			->join('u.person', 'person')
			->join('person.members', 'member')
			->join('member.organization', 'organisation')
			->andWhere($expr->like('organisation.code', $expr->literal($code)))
			->andWhere($expr->like('person.idNumber', $expr->literal($nric)))
			->getQuery();

//		return $qb->execute();
		
		// to get just one result:
		return $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();
	}
}