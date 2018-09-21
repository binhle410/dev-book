<?php

namespace Magenta\Bundle\CBookModelBundle\Doctrine\Book;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\BookCategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BookListener {
	/**
	 * @var ContainerInterface
	 */
	private $container;
	
	function __construct(ContainerInterface $container) {
		$this->container = $container;
	}
	
	private function updateInfoAfterOperation(Book $book, LifecycleEventArgs $event) {
		$this->updateInfo($book, $event);
		$manager  = $event->getEntityManager();
		$registry = $this->container->get('doctrine');
	}
	
	private function updateInfo(Book $book, LifecycleEventArgs $event) {
	}
	
	private function updateInfoBeforeOperation(Book $book, LifecycleEventArgs $event) {
		$this->updateInfo($book, $event);
		/** @var EntityManager $manager */
		$manager       = $event->getObjectManager();
		$registry      = $this->container->get('doctrine');
		$categoryItems = $book->getBookCategoryItems();
		$uow           = $manager->getUnitOfWork();
		
		/** @var BookCategoryItem $item */
		foreach($categoryItems as $item) {
			$item->setItem($book);
			$manager->persist($item);
			$uow->computeChangeSet($manager->getClassMetadata(BookCategoryItem::class), $item);
//			 recompute alone won't work
//			$uow->recomputeSingleEntityChangeSet($manager->getClassMetadata(BookCategoryItem::class), $item);
		}
		
	}
	
	
	public function preFlushHandler(Book $book, PreFlushEventArgs $event) {
		$manager  = $event->getEntityManager();
		$registry = $this->container->get('doctrine');
		
		
	}
	
	public function preUpdateHandler(Book $book, LifecycleEventArgs $event) {
		$this->updateInfoBeforeOperation($book, $event);
	}
	
	public function postUpdateHandler(Book $book, LifecycleEventArgs $event) {
		$this->updateInfoAfterOperation($book, $event);
	}
	
	public function prePersistHandler(Book $book, LifecycleEventArgs $event) {
		$this->updateInfoBeforeOperation($book, $event);
		$manager  = $event->getObjectManager();
		$registry = $this->container->get('doctrine');
		$bookRepo = $registry->getRepository(Person::class);
		$userRepo = $registry->getRepository(User::class);
		$uow      = $manager->getUnitOfWork();
		if($book->getBookCategoryItems()->count() === 0) {
			$rootCategory = $registry->getRepository(Category::class)->findOneBy([
				'parent'       => null,
				'context'      => Context::DEFAULT_CONTEXT,
				'organisation' => $book->getOrganisation(),
				'enabled'      => true
			]);
			$categoryItem = new BookCategoryItem();
			$categoryItem->setCategory($rootCategory);
			$categoryItem->setItem($book);
			$book->addBookCategoryItem($categoryItem);
			$manager->persist(($categoryItem));
		}
	}
	
	public function postPersistHandler(Book $book, LifecycleEventArgs $event) {
		$this->updateInfoAfterOperation($book, $event);
		/** @var EntityManager $manager */
		$manager  = $event->getObjectManager();
		$registry = $this->container->get('doctrine');
		$bookRepo = $registry->getRepository(Person::class);
		$userRepo = $registry->getRepository(User::class);
		$uow      = $manager->getUnitOfWork();
	}
	
	public function preRemoveHandler(Book $book, LifecycleEventArgs $event) {
	}
	
	public function postRemoveHandler(Book $book, LifecycleEventArgs $event) {
	}
	
	public
	function postLoadHandler(
		Book $book, LifecycleEventArgs $event
	) {
		/** @var EntityManager $manger */
		$manager = $event->getEntityManager();
		
	}
}
