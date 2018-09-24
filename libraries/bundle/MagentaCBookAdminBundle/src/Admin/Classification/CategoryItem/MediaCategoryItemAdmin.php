<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification\CategoryItem;
use Doctrine\ORM\Query\Expr;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookAdminBundle\Admin\Classification\CategoryItemAdmin;
use Magenta\Bundle\CBookAdminBundle\Form\Type\OrgAwareCategorySelectorType;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MediaCategoryItemAdmin extends CategoryItemAdmin {
	const AUTO_CONFIG = true;
	
	protected function configureRoutes(RouteCollection $collection) {
		parent::configureRoutes($collection);
	}
}
