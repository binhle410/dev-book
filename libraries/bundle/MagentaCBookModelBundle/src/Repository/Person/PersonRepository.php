<?php

namespace Magenta\Bundle\CBookModelBundle\Repository\Person;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PersonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
     * @param null $idNumber
     * @param null $email
     * @return Person|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOnePersonByIdNumberOrEmail($idNumber = null, $email = null)
    {
        $qb = $this->createQueryBuilder('p');
        $expr = $qb->expr();

        if (empty($idNumber) && empty($email)) {
            throw new \InvalidArgumentException();
        }

        if (!(empty($idNumber) || empty($email))) {
            $qb->where($expr->orX(
                $expr->eq('idNumber', $idNumber),
                $expr->eq('email', $email)
            ));
        } elseif (empty($idNumber)) {
            $qb->where($expr->eq('email', $email));
        } else {
            $qb->where($expr->eq('idNumber', $idNumber));
        }
// $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();
        $r = $qb->getQuery()->getResult();
        if (count($r) === 0) {
            return null;
        }
        $person = null;
        /** @var Person $_person */
        foreach ($r as $_person) {
            $person = $_person;
            if (!empty($person->getEmail())) {
                break;
            }
        }
        return $person;
    }
}