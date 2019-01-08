<?php

namespace Magenta\Bundle\CBookModelBundle\Doctrine\Person;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PersonListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    private $personService;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->personService = $container->get('magenta_book.person_service');
    }

    private function updateInfoAfterOperation(Person $person, LifecycleEventArgs $event)
    {
        $this->updateInfo($person, $event);
        $manager = $event->getEntityManager();
        $registry = $this->container->get('doctrine');
    }

    private function updateInfo(Person $person, LifecycleEventArgs $event)
    {
        if (empty($person->getEmail())) {
            $fname = strtolower($person->getGivenName());
            if (!empty($fname)) {
                $fname = str_replace(' ', '-', $fname);
            }
            $lname = strtolower($person->getFamilyName());
            if (!empty($lname)) {
                $lname = str_replace(' ', '-', $lname);
            }
            $rand = rand(0, 1000);
            $person->setEmail($fname.'-'.$lname.'-'.$rand.'@no-email.com');
        }
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
            $user->setPerson($person); // to make sure person is persisted with the user
            
            if (null !== ($pass = $user->getPlainPassword())) {
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
            if (!empty($person->getEmail())) {
                $this->initiateUserFromPerson($person, $manager);
            }
        }
    }

    protected function initiateUserFromPerson(Person $person, ObjectManager $manager)
    {
        $user = $this->personService->initiateUser($person);
        $manager->persist($user);
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

    public function postLoadHandler(
        Person $person, LifecycleEventArgs $event
    ) {
        /** @var EntityManager $manger */
        $manager = $event->getEntityManager();
        if (!empty($person->getEmail())) {
            $this->initiateUserFromPerson($person, $manager);
        }
    }
}
