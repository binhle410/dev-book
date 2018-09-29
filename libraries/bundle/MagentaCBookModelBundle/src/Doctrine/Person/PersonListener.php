<?php

namespace Magenta\Bundle\CBookModelBundle\Doctrine\Person;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PersonListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function updateInfoAfterOperation(Person $person, LifecycleEventArgs $event)
    {
        $this->updateInfo($person, $event);
        $manager = $event->getEntityManager();
        $registry = $this->container->get('doctrine');
    }

    private function updateInfo(Person $person, LifecycleEventArgs $event)
    {
    }

    private function updateInfoBeforeOperation(Person $person, LifecycleEventArgs $event)
    {
        $this->updateInfo($person, $event);
        /** @var EntityManager $manager */
        $manager = $event->getObjectManager();
        $registry = $this->container->get('doctrine');
        $personRepo = $registry->getRepository(Person::class);
        $userRepo = $registry->getRepository(User::class);
        $email = $person->getEmail();
        $uow = $manager->getUnitOfWork();
        if (!empty($user = $person->getUser())) {
            if (($pass = $user->getPlainPassword()) !== null) {
                if (empty($pass)) {
                    $pass = null;
                }
            }
            if (empty($user->getEmail()) && !empty($person->getEmail())) {
                $user->setEmail($person->getEmail());
            }
            if (!empty($userEmail = $user->getEmail()) && ($userEmail !== $email || empty($user->getId()))) {
                /** @var User $fUser */
                $fUser = $userRepo->findOneByEmail($userEmail);
                if (!empty($fUser)) {
                    $fUser->setPlainPassword($pass);
                    $person->setUser($fUser);
                } else {
                    $user->setPlainPassword($pass);
                    $manager->persist($user);
                }
            } elseif (!empty($pass)) {
                $user->setPlainPassword($pass);
                $this->container->get('magenta_user.util.password_updater')->hashPassword($user); // do you think persist will work without this line?
                // which line of code below (1 - 4) is unnecessary.
                $manager->persist($user); // 1
                $uow->computeChangeSet($manager->getClassMetadata(User::class), $user); // 2
                $uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(User::class), $user); // 3
                if (!empty($user->getId())) {
                    $manager->flush($user); // 4
                }
            }
        } else {
            $this->initiateUserFromPerson($person, $manager);
        }
    }

    protected function initiateUserFromPerson(Person $person, ObjectManager $manager)
    {
        if (!$person->isSystemUserPersisted()) {
            $userRepo = $this->container->get('doctrine')->getRepository(User::class);
            $email = $person->getEmail();

            // person null - user null
            $pu = $person->initiateUser();
            $manager->persist($pu); // to be detached later in case
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
                $manager->detach($pu);
                if (!empty($pass = $pu->getPlainPassword())) {
                    $user->setPlainPassword($pass);
                }
                $person->setUser($user);
                $user->setPerson($person);
//				$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(Person::class), $person);
                $manager->persist($user);
//				$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(User::class), $user);
            }
        }

    }

    public function preFlushHandler(Person $person, PreFlushEventArgs $event)
    {
        $manager = $event->getEntityManager();
        $registry = $this->container->get('doctrine');
        $email = $person->getEmail();


    }

    public function preUpdateHandler(Person $person, LifecycleEventArgs $event)
    {
        $this->updateInfoBeforeOperation($person, $event);
    }

    public function postUpdateHandler(Person $person, LifecycleEventArgs $event)
    {
        $this->updateInfoAfterOperation($person, $event);
    }

    public function prePersistHandler(Person $person, LifecycleEventArgs $event)
    {
        $this->updateInfoBeforeOperation($person, $event);
        $manager = $event->getObjectManager();
        $registry = $this->container->get('doctrine');
        $personRepo = $registry->getRepository(Person::class);
        $userRepo = $registry->getRepository(User::class);
        $uow = $manager->getUnitOfWork();
        $email = $person->getEmail();
    }

    public function postPersistHandler(Person $person, LifecycleEventArgs $event)
    {
        $this->updateInfoAfterOperation($person, $event);
        /** @var EntityManager $manager */
        $manager = $event->getObjectManager();
        $registry = $this->container->get('doctrine');
        $personRepo = $registry->getRepository(Person::class);
        $userRepo = $registry->getRepository(User::class);
        $email = $person->getEmail();
        $uow = $manager->getUnitOfWork();
        $personCS = $uow->getEntityChangeSet($person);
        if ($person->isSystemUserPersisted()) {
            $pu = $person->getUser();
            $pu->addRole(User::ROLE_POWER_USER);
            $pu->setEmail($email);
            $manager->persist($pu);
//			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(User::class), $pu);
        }
    }

    public function preRemoveHandler(Person $person, LifecycleEventArgs $event)
    {
    }

    public function postRemoveHandler(Person $person, LifecycleEventArgs $event)
    {
    }

    public
    function postLoadHandler(
        Person $person, LifecycleEventArgs $event
    )
    {
        /** @var EntityManager $manger */
        $manager = $event->getEntityManager();
        $this->initiateUserFromPerson($person, $manager);
    }
}
