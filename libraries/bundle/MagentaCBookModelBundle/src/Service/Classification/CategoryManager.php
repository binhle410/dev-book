<?php

namespace Magenta\Bundle\CBookModelBundle\Service\Classification;

use Doctrine\Common\Persistence\ManagerRegistry;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Sonata\ClassificationBundle\Model\ContextInterface;
use Sonata\ClassificationBundle\Model\ContextManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CategoryManager extends \Sonata\ClassificationBundle\Entity\CategoryManager {
	/** @var ContainerInterface */
	private $container;
	
	public function __construct(string $class, ManagerRegistry $registry, ContextManagerInterface $contextManager, ContainerInterface $container) {
		parent::__construct($class, $registry, $contextManager);
		$this->container = $container;
	}
	
	/**
	 * Load all categories from the database, the current method is very efficient for < 256 categories.
	 * have to sync with its parent method
	 *
	 * @param bool $loadChildren
	 *
	 * @return array|\Sonata\ClassificationBundle\Model\CategoryInterface[]
	 */
	public function getAllRootCategories($loadChildren = true) {
		if(empty($orgId = $this->container->get('request_stack')->getCurrentRequest()->get('organisation'))) {
			throw new UnauthorizedHttpException('No Org Info');
		}
		
		$class = $this->getClass();
		
		$rootCategories = $this->getObjectManager()->createQuery(sprintf('SELECT c FROM %s c WHERE c.parent IS NULL AND c.organisation = :org', $class))
		                       ->setParameter('org', $orgId)
		                       ->execute();
		
		$categories = [];
		
		foreach($rootCategories as $category) {
			if(null === $category->getContext()) {
				throw new \RuntimeException('Context cannot be null');
			}
			
			$categories[] = $loadChildren ? $this->getRootCategoryWithChildren($category) : $category;
		}
		
		return $categories;
	}
	
	
	/**
	 * Load all categories from the database, the current method is very efficient for < 256 categories.
	 * have to sync with its parent method
	 *
	 * @param ContextInterface $context
	 */
	protected function loadCategories(ContextInterface $context) {
		if(array_key_exists($context->getId(), $this->categories)) {
			return;
		}
		
		
		if(empty($orgId = $this->container->get('request_stack')->getCurrentRequest()->get('organisation'))) {
			throw new UnauthorizedHttpException('No Org Info');
		}
		$class = $this->getClass();
		
		$categories = $this->getObjectManager()->createQuery(sprintf('SELECT c FROM %s c WHERE c.context = :context AND c.organisation = :org ORDER BY c.parent ASC', $class))
		                   ->setParameter('context', $context->getId())
		                   ->setParameter('org', $orgId)
		                   ->execute();
		
		if(0 == count($categories)) {
			// no category, create one for the provided context
			
			if(empty($organisation = $this->container->get('doctrine')->getRepository(Organisation::class)->find($orgId))) {
				throw new UnauthorizedHttpException('Org not found');
			}
			$category = $this->create();
			$category->setName('Root');
			$category->setEnabled(true);
			$category->setContext($context);
			$category->setDescription($context->getName());
			$category->setOrganisation($organisation);
			
			$this->save($category);
			
			$categories = [ $category ];
		}
		
		$rootCategories = [];
		foreach($categories as $pos => $category) {
			if(null === $category->getParent()) {
				$rootCategories[] = $category;
			}
			
			$this->categories[ $context->getId() ][ $category->getId() ] = $category;
			
			$parent = $category->getParent();
			
			$category->disableChildrenLazyLoading();
			
			if($parent) {
				$parent->addChild($category);
			}
		}
		
		$this->categories[ $context->getId() ] = $rootCategories;
	}
}
