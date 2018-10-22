<?php

namespace Magenta\Bundle\CBookModelBundle\Doctrine\Book;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\BookCategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ChapterListener {
	/**
	 * @var ContainerInterface
	 */
	private $container;
	
	function __construct(ContainerInterface $container) {
		$this->container = $container;
	}
	
	private function updateInfoAfterOperation(Chapter $chapter, LifecycleEventArgs $event) {
		$this->updateInfo($chapter, $event);
		$manager  = $event->getEntityManager();
		$registry = $this->container->get('doctrine');
	}
	
	private function updateInfo(Chapter $chapter, LifecycleEventArgs $event) {
	}
	
	private function updateInfoBeforeOperation(Chapter $chapter, LifecycleEventArgs $event) {
		$this->updateInfo($chapter, $event);
		/** @var EntityManager $manager */
		$manager       = $event->getObjectManager();
		$registry      = $this->container->get('doctrine');
		$uow           = $manager->getUnitOfWork();

	}
	
	
	public function preFlushHandler(Chapter $chapter, PreFlushEventArgs $event) {
		$manager  = $event->getEntityManager();
		$registry = $this->container->get('doctrine');
		
		
	}
	
	public function preUpdateHandler(Chapter $chapter, LifecycleEventArgs $event) {
		$this->updateInfoBeforeOperation($chapter, $event);
	}
	
	public function postUpdateHandler(Chapter $chapter, LifecycleEventArgs $event) {
		$this->updateInfoAfterOperation($chapter, $event);
	}
	
	public function prePersistHandler(Chapter $chapter, LifecycleEventArgs $event) {
		$this->updateInfoBeforeOperation($chapter, $event);
		$manager  = $event->getObjectManager();
		$registry = $this->container->get('doctrine');
		$chapterRepo = $registry->getRepository(Person::class);
		$userRepo = $registry->getRepository(User::class);
		$uow      = $manager->getUnitOfWork();

	}
	
	public function postPersistHandler(Chapter $chapter, LifecycleEventArgs $event) {
		$this->updateInfoAfterOperation($chapter, $event);
		/** @var EntityManager $manager */
		$manager  = $event->getObjectManager();
		$registry = $this->container->get('doctrine');
		$chapterRepo = $registry->getRepository(Person::class);
		$userRepo = $registry->getRepository(User::class);
		$uow      = $manager->getUnitOfWork();
	}
	
	public function preRemoveHandler(Chapter $chapter, LifecycleEventArgs $event) {
	}
	
	public function postRemoveHandler(Chapter $chapter, LifecycleEventArgs $event) {
	}
	
	public
	function postLoadHandler(
		Chapter $chapter, LifecycleEventArgs $event
	) {
		/** @var EntityManager $manger */
		$manager = $event->getEntityManager();
		
	}
}
