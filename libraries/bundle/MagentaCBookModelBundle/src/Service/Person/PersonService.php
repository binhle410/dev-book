<?php

namespace Magenta\Bundle\CBookModelBundle\Service\Person;

use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\BaseService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PersonService extends BaseService
{

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    public function initiateUser(Person $person)
    {
        if (!$person->isSystemUserPersisted()) {
            $userRepo = $this->container->get('doctrine')->getRepository(User::class);
            $email = $person->getEmail();

            // person null - user null
            $pu = $person->initiateUser();
//            $manager->persist($pu); // to be detached later in case
//			$manager->flush($pu);

            /**
             * This is only necessary if we cascade persist from person to user
             */
//			if(empty($pu = $person->getUser())) {
//				$pu = $person->initiateUser();
//				$manager->persist($pu);
//				$manager->flush($pu);
//			} else {
//				$uow->computeChangeSet($manager->getClassMetadata(User::class), $pu);
//				$pu = $person->initiateUser();
//				$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(User::class), $pu);
//			}

            /** @var User $user */
            $user = $userRepo->findOneBy(['email' => $email]);
            if (!empty($user)) {
                $pu->setPerson(null);
//                $manager->detach($pu);
                if (!empty($pass = $pu->getPlainPassword())) {
                    $user->setPlainPassword($pass);
                }
                $person->setUser($user);
                $user->setPerson($person);
//				$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(Person::class), $person);
//				$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(User::class), $user);
                $person->setPersisted(true);
                return $user;
            }
            $person->setPersisted(true);
            return $pu;
        }
        return $person->getUser();
    }

}