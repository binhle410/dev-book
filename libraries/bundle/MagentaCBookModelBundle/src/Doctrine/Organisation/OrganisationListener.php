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

class OrganisationListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function updateInfoAfterOperation(Organisation $organisation, LifecycleEventArgs $event)
    {
        $this->updateInfo($organisation, $event);
    }

    private function updateInfo(Organisation $organisation, LifecycleEventArgs $event)
    {

    }

    private
    function updateInfoBeforeOperation(
        Organisation $organisation, LifecycleEventArgs $event
    )
    {
        $this->updateInfo($organisation, $event);

//			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(Person::class), $person); // Cannot call recomputeSingleEntityChangeSet before computeChangeSet on an entity.
//			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(Person::class), $m_person); // Cannot call recomputeSingleEntityChangeSet before computeChangeSet on an entity.
//			$manager->persist($organisation);
//			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(IndividualMember::class), $organisation); // Cannot call recomputeSingleEntityChangeSet before computeChangeSet on an entity.

    }

    public
    function preUpdateHandler(
        Organisation $organisation, LifecycleEventArgs $event
    )
    {
        $this->updateInfoBeforeOperation($organisation, $event);


    }

    public
    function postUpdateHandler(
        Organisation $organisation, LifecycleEventArgs $event
    )
    {
        $this->updateInfoAfterOperation($organisation, $event);
        $manager = $event->getEntityManager();
        $uow = $manager->getUnitOfWork();

    }

    public
    function prePersistHandler(
        Organisation $organisation, LifecycleEventArgs $event
    )
    {
        $this->updateInfoBeforeOperation($organisation, $event);
    }

    public
    function postPersistHandler(
        Organisation $organisation, LifecycleEventArgs $event
    )
    {
        $this->updateInfoAfterOperation($organisation, $event);
    }

    public
    function preRemoveHandler(
        Organisation $organisation, LifecycleEventArgs $event
    )
    {
    }

    public
    function postRemoveHandler(
        Organisation $organisation, LifecycleEventArgs $event
    )
    {
    }

    public
    function postLoadHandler(
        Organisation $organisation, LifecycleEventArgs $args
    )
    {

    }
}
