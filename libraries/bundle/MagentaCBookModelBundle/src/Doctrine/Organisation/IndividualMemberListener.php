<?php

namespace Magenta\Bundle\CBookModelBundle\Doctrine\Organisation;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IndividualMemberListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function updateInfoAfterOperation(IndividualMember $member, LifecycleEventArgs $event)
    {
        $this->updateInfo($member, $event);
    }

    private function updateInfo(IndividualMember $member, LifecycleEventArgs $event)
    {
        $member->initiateCode()->initiatePin();

        /** @var Person $person */
        $person = $member->getPerson();
        $email = $member->getEmail();
        $pEmail = null;

        if (!empty($person)) {
            $pEmail = $person->getEmail();
        }

        if (!empty($email) && !(empty($person) || $pEmail === $email)) {
            if (empty($pEmail)) {
                $person->setEmail($email);
            }
        }
    }

    private
    function updateInfoBeforeOperation(
        IndividualMember $member, LifecycleEventArgs $event
    )
    {
        $this->updateInfo($member, $event);
        /** @var Person $person */
        $person = $member->getPerson();
        $email = $member->getEmail();
        $pEmail = null;
        $registry = $this->container->get('doctrine');
        /** @var EntityManager $manager */
        $manager = $event->getObjectManager();
        $uow = $manager->getUnitOfWork();

        $personRepo = $registry->getRepository(Person::class);

        if (!empty($person)) {
            $pEmail = $person->getEmail();
        }

        if (!empty($email) && (empty($person->getId()) || !(empty($person) || $pEmail === $email))) {
            /** @var Person $m_person */
            if (empty($m_person = $personRepo->findOneBy(['email' => $email]))) {
                $m_person = new Person();
                $m_person->copyScalarPropertiesFrom($person);
                $m_person->setEmail($email);
            }
            if (!empty($person->getId())) {
                $personCS = $uow->getEntityChangeSet($person);
                if (array_key_exists('name', $personCS)) {
                    $person->setName($personCS['name'][0]);
//				$uow->getEntityChangeSet($person)['name'][1] = $personCS['name'][0];
                    $uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(Person::class), $person); // Cannot call recomputeSingleEntityChangeSet before computeChangeSet on an entity.
                    $manager->persist($person);
                }
            }

            $person->removeIndividualMember($member);
            $m_person->addIndividualMember($member);
            $manager->persist($m_person);
//			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(Person::class), $person); // Cannot call recomputeSingleEntityChangeSet before computeChangeSet on an entity.
//			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(Person::class), $m_person); // Cannot call recomputeSingleEntityChangeSet before computeChangeSet on an entity.
//			$manager->persist($member);
//			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(IndividualMember::class), $member); // Cannot call recomputeSingleEntityChangeSet before computeChangeSet on an entity.
        }
    }

    public
    function preUpdateHandler(
        IndividualMember $member, LifecycleEventArgs $event
    )
    {
        $this->updateInfoBeforeOperation($member, $event);


    }

    public
    function postUpdateHandler(
        IndividualMember $member, LifecycleEventArgs $event
    )
    {
        $this->updateInfoAfterOperation($member, $event);
        $manager = $event->getEntityManager();
        $uow = $manager->getUnitOfWork();
        $manager->persist($person = $member->getPerson());
        $manager->flush($person);
    }

    public
    function prePersistHandler(
        IndividualMember $member, LifecycleEventArgs $event
    )
    {
        $this->updateInfoBeforeOperation($member, $event);
    }

    public
    function postPersistHandler(
        IndividualMember $member, LifecycleEventArgs $event
    )
    {
        $this->updateInfoAfterOperation($member, $event);
    }

    public
    function preRemoveHandler(
        IndividualMember $member, LifecycleEventArgs $event
    )
    {
    }

    public
    function postRemoveHandler(
        IndividualMember $member, LifecycleEventArgs $event
    )
    {
    }

    public
    function postLoadHandler(
        IndividualMember $member, LifecycleEventArgs $args
    )
    {
        if (empty($member->getPin()) || empty($member->getCode())) {
            $member->initiateCode()->initiatePin();
            $manager = $args->getEntityManager();
            $manager->persist($member);
            $manager->flush($member);
        }
    }
}
