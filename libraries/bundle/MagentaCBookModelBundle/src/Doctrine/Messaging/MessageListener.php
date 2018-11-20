<?php

namespace Magenta\Bundle\CBookModelBundle\Doctrine\Messaging;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\BookCategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Conversation;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\SonataNotificationMessage;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing\DPJob;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MessageListener
{
    
    /**
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * @var Registry
     */
    private $registry;
    
    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->registry = $container->get('doctrine');
    }
    
    private function updateInfoAfterOperation(Message $message, LifecycleEventArgs $event)
    {
        $this->updateInfo($message, $event);
        $manager = $event->getEntityManager();
        $registry = $this->container->get('doctrine');
    }
    
    private function updateInfo(Message $message, LifecycleEventArgs $event)
    {
    }
    
    private function updateInfoBeforeOperation(Message $message, LifecycleEventArgs $event)
    {
        $this->updateInfo($message, $event);
        /** @var EntityManager $manager */
        $manager = $event->getEntityManager();
        $registry = $this->container->get('doctrine');
        $uow = $manager->getUnitOfWork();
        
        $conversation = null;
        if (empty($message->getConversation())) {
            /** @var Organisation $org */
            $org = $message->getOrganization();
            if (!empty($org)) {
                if (empty($conversation = $org->getPublicConversation())) {
                    $c = new Conversation();
                    $c->setOrganisation($org);
                    $c->setEnabled(true);
                    $c->setName('Public Conversation');
                    
                    $conversation = $c;
                    $manager->persist($conversation);
                }
                $message->setConversation($conversation);
            }
        }
    }
    
    
    public function preFlushHandler(Message $message, PreFlushEventArgs $event)
    {
        $manager = $event->getEntityManager();
        $registry = $this->container->get('doctrine');
        
        
    }
    
    public function preUpdateHandler(Message $message, LifecycleEventArgs $event)
    {
        $this->updateInfoBeforeOperation($message, $event);
    }
    
    public function postUpdateHandler(Message $message, LifecycleEventArgs $event)
    {
        $this->updateInfoAfterOperation($message, $event);
    }
    
    public function prePersistHandler(Message $message, LifecycleEventArgs $event)
    {
        $this->updateInfoBeforeOperation($message, $event);
        $manager = $event->getObjectManager();
        $registry = $this->container->get('doctrine');
        $messageRepo = $registry->getRepository(Person::class);
        $userRepo = $registry->getRepository(User::class);
        $uow = $manager->getUnitOfWork();
        
    }
    
    public function postPersistHandler(Message $message, LifecycleEventArgs $event)
    {
        $this->updateInfoAfterOperation($message, $event);
        /** @var EntityManager $manager */
        $manager = $event->getObjectManager();
        $registry = $this->container->get('doctrine');
        $messageRepo = $registry->getRepository(Person::class);
        $userRepo = $registry->getRepository(User::class);
        $uow = $manager->getUnitOfWork();
    }
    
    public function preRemoveHandler(Message $message, LifecycleEventArgs $event)
    {
    }
    
    public function postRemoveHandler(Message $message, LifecycleEventArgs $event)
    {
    }
    
    public
    function postLoadHandler(
        Message $message, LifecycleEventArgs $event
    )
    {
        /** @var EntityManager $manger */
        $manager = $event->getEntityManager();
        if ($message->getStatus() === Message::STATUS_NEW) {
            $message->markStatusAsDeliveryInProgress();
            $dpJobRepo = $this->registry->getRepository(DPJob::class);
            $dp = $dpJobRepo->findOneBy(['type' => DPJob::TYPE_PWA_PUSH_ORG_INDIVIDUAL, 'resourceName' => $message->getId()]);
            if (empty($dp)) {
                $dp = DPJob::createInstance($message->getId(), DPJob::TYPE_PWA_PUSH_ORG_INDIVIDUAL, $message->getOrganisation()->getId());
                $manager->persist($dp);
                $manager->flush($dp);
                
                $this->container->get('sonata.notification.backend')->createAndPublish(SonataNotificationMessage::TYPE_PWA_PUSH_NOTIFICATION, array(
                    'job-id' => $dp->getId()
                ));
            }
            
            $manager->persist($message);
            $manager->flush($message);
        }
    }
}
