<?php

namespace Magenta\Bundle\CBookModelBundle\Doctrine\Media;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MediaListener
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    private function updateInfoAfterOperation(Media $media, LifecycleEventArgs $event)
    {
        $this->updateInfo($media, $event);
    }

    private function updateInfo(Media $media, LifecycleEventArgs $event)
    {
    }

    private function updateInfoBeforeOperation(
        Media $media, LifecycleEventArgs $event
    ) {
        $this->updateInfo($media, $event);
        $catManager = $this->container->get('sonata.media.manager.category');

        if (!$media->getCategory()) {
            $cid = $media->getContext();
            $orgId = $media->getOrganization()->getId();
//            if (!empty($orgId = $media->getOrganization()->getId())) { // loop here
            $rootCategory = $this->container->get('doctrine')->getRepository(Category::class)->findOneBy(['parent' => null, 'context' => $cid, 'organisation' => $orgId]);
//            }
            if (empty($rootCategory)) {
                $catManager->initiateRootCategories($cid);
                /** @var Category $rootCategory */
                $rootCategory = $this->container->get('doctrine')->getRepository(Category::class)->findOneBy(['parent' => null, 'context' => $cid, 'organisation' => $media->getOrganization()->getId()]);
            }
            $media->setCategory($rootCategory);
        }

        //			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(Person::class), $person); // Cannot call recomputeSingleEntityChangeSet before computeChangeSet on an entity.
//			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(Person::class), $m_person); // Cannot call recomputeSingleEntityChangeSet before computeChangeSet on an entity.
//			$manager->persist($media);
//			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(IndividualMember::class), $media); // Cannot call recomputeSingleEntityChangeSet before computeChangeSet on an entity.
    }

    public function preUpdateHandler(
        Media $media, LifecycleEventArgs $event
    ) {
        $this->updateInfoBeforeOperation($media, $event);
    }

    public function postUpdateHandler(
        Media $media, LifecycleEventArgs $event
    ) {
        $this->updateInfoAfterOperation($media, $event);
        $manager = $event->getEntityManager();
        $uow = $manager->getUnitOfWork();
    }

    public function prePersistHandler(
        Media $media, LifecycleEventArgs $event
    ) {
        $this->updateInfoBeforeOperation($media, $event);
    }

    public function postPersistHandler(
        Media $media, LifecycleEventArgs $event
    ) {
        $this->updateInfoAfterOperation($media, $event);
    }

    public function preRemoveHandler(
        Media $media, LifecycleEventArgs $event
    ) {
    }

    public function postRemoveHandler(
        Media $media, LifecycleEventArgs $event
    ) {
    }

    public function postLoadHandler(
        Media $media, LifecycleEventArgs $args
    ) {
    }
}
