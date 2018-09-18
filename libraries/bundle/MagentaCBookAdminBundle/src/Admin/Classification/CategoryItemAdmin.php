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
	
	public const AUTO_CONFIG = false;
	
	/**
	 * @return CategoryItem|null|object
	 */
	function getSubject() {
		return parent::getSubject();
	}
	
	protected function configureFormFields(FormMapper $formMapper) {
		$formMapper->add('category', OrgAwareCategorySelectorType::class, [
			'organisation'  => $this->getCurrentOrganisation(),
			'category'      => $this->getSubject() ? $this->getSubject()->getCategory() : null,
			'model_manager' => $this->getModelManager(),
			'class'         => $this->getClass(),
			'required'      => true,
			'context'       => $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository(Context::class)->find(Context::DEFAULT_CONTEXT),
			'btn_add'       => false
		]);

//		$formMapper->add('item');
//		$keys = $formMapper->keys();
//		$key  = array_pop($keys);
//		array_unshift($keys, $key);
//		$formMapper->reorder($keys);
	}
}
