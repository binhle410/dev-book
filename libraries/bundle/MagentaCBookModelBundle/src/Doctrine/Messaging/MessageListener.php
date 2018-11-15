<?php

namespace Magenta\Bundle\CBookModelBundle\Doctrine\Book;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\BookCategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Conversation;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MessageListener
{
    
    /**
     * @var ContainerInterface
     */
    private $container;
    
    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        $manager = $event->getObjectManager();
        $registry = $this->container->get('doctrine');
        $categoryItems = $message->getBookCategoryItems();
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
        
    }
}
