<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification;

use Doctrine\ORM\Query\Expr;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookAdminBundle\Form\Type\OrgAwareCategorySelectorType;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CategoryItemAdmin extends BaseAdmin {

//	public const AUTO_CONFIG = false;
	
	/**
	 * @return CategoryItem|null|object
	 */
	function getSubject() {
		return parent::getSubject();
	}
	
	/**
	 * @param CategoryItem $object
	 */
	public function toString($object) {
		return $object->getItem()->getName();
	}
	
	protected function configureRoutes(RouteCollection $collection) {
		$collection->remove('list');
		$collection->add('newInstance', 'new-instance');
	}
	
	protected function configureFormFields(FormMapper $formMapper) {
		$container = $this->getConfigurationPool()->getContainer();
		$registry  = $container->get('doctrine');
		
		
		/** @var Context $defaultContext */
		$defaultContext      = $registry->getRepository(Context::class)->find(Context::DEFAULT_CONTEXT);
		$rootCategory        = $registry->getRepository(Category::class)->findOneBy([
			'context' => $defaultContext,
			'parent'  => null
		]);
		$currentOrganisation = $this->getCurrentOrganisation();
		$formMapper->add('category', OrgAwareCategorySelectorType::class, [
			'label'         => 'form.label_name',
			'organisation'  => $currentOrganisation,
			'category'      => $rootCategory,
			'model_manager' => $container->get('sonata.classification.admin.category')->getModelManager(),
			'class'         => Category::class,
			'required'      => true,
			'context'       => $defaultContext,
			'btn_add'       => false
		]);

//		$formMapper->add('item');
//		$keys = $formMapper->keys();
//		$key  = array_pop($keys);
//		array_unshift($keys, $key);
//		$formMapper->reorder($keys);
	}
}
