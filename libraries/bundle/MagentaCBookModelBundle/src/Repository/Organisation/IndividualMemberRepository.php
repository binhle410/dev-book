<?php

namespace Magenta\Bundle\CBookModelBundle\Repository\Organisation;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Symfony\Bridge\Doctrine\RegistryInterface;

class IndividualMemberRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, IndividualMember::class);
    }
    
    public function findHavingOrganisationSubscriptions($orgId)
    {
        $qb = $this->createQueryBuilder('individual_member')
            ->join('individual_member.organization', 'organization')
            ->join('individual_member.subscriptions', 'subscriptions');
        $expr = $qb->expr();
        $qb
            ->where($expr->eq('organization.id', $orgId));
//        if (!empty($message)) {
//            if ($delivered === false) {
//                $qb
//                    ->leftJoin('subscriptions.deliveries', 'delivery')
//                    ->leftJoin('delivery.message','message')
//                    ->andWhere($expr->isNull('delivery'))
//                ;
//
//            } elseif ($delivered === true) {
//                $qb->andWhere($expr->isNotNull('deliveries'));
//            }
//        }
        return $qb->getQuery()->getResult();
    }
    
    /**
     * @param $pin
     * @param $employeeCode
     * @return IndividualMember
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByPinCodeEmployeeCode($pin, $employeeCode)
    {
        $qb = $this->createQueryBuilder('m');
        $expr = $qb->expr();
        $qb
            ->andWhere($expr->like('m.pin', $expr->literal($pin)))
            ->andWhere($expr->like('m.code', $expr->literal($employeeCode)));

//		return $qb->execute();
//$query = $qb->getQuery()->getSQL();
        // to get just one result:
        return $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();
        
    }
    
    public function findOneByOrganisationCodeNric($code, $nric)
    {
        $qb = $this->createQueryBuilder('m');
        $expr = $qb->expr();
        $qb
            ->join('m.person', 'person')
            ->join('m.organization', 'organisation')
            ->andWhere($expr->like('organisation.code', $expr->literal($code)))
            ->andWhere($expr->like('person.idNumber', $expr->literal($nric)));

//		return $qb->execute();
        
        // to get just one result:
        return $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();
    }
}