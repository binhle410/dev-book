<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification;

use Bean\Component\Organization\IoC\OrganizationAwareInterface;
use Doctrine\ORM\Query\Expr;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdminTrait;
use Magenta\Bundle\CBookAdminBundle\Form\Type\OrgAwareCategorySelectorType;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\ClassificationBundle\Admin\CategoryAdmin as SonataCategoryAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\ClassificationBundle\Form\Type\CategorySelectorType;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Valid;

class CategoryAdmin extends SonataCategoryAdmin {
	use BaseAdminTrait {
		getOrganisationFieldName as protected getOrganisationFieldNameTrait;
		configureRoutes as protected configureRoutesTrait;
//		configureFormFields as protected configureFormFieldsTrait;
	}

    /**
     * @var Category $subject
     */
	protected $subject;
	
	protected function getOrganisationFieldName($class) {
		return 'organisation';
	}
	
	public function configureRoutes(RouteCollection $collection) {
		parent::configureRoutes($collection);
		$collection->add('createCategory');
	}
	
	protected function configureTabMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null) {
		parent::configureTabMenu($menu, $action, $childAdmin);
	}
	
	protected function configureFormFields(FormMapper $formMapper) {
		parent::configureFormFields($formMapper);
		$formMapper->remove('parent');
		$formMapper->remove('media');
		$formMapper->remove('position');
		/** @var Category $subject */
		$subject  = $this->getSubject();
		$position = $this->hasSubject() && null !== $this->getSubject()->getPosition() ? $this->getSubject()->getPosition() : 0;
		$formMapper->with('Options', [])
		           ->add('enabled', CheckboxType::class, [
			           'required' => false,
			           'help'     => 'If a book is not enabled, no one can view it except Admins.'
		           ])
		           ->add('public', CheckboxType::class, [
			           'required' => false,
			           'help'     => 'To set whether a book is public or private.'
		           ])
		           ->add('accessGrantedGroups', ModelType::class, [
			           'btn_add'  => false,
			           'required' => false,
			           'property' => 'name',
			           'multiple' => true,
			           'help'     => 'Access Granted Groups enable the selected groups to view private books. This has no effects when a book is public.'
		
		           ])
		           ->add('accessDeniedGroups', ModelType::class, [
			           'btn_add'  => false,
			           'required' => false,
			           'property' => 'name',
			           'multiple' => true,
			           'help'     => 'Access Denied Groups prevent the selected groups from viewing public books. This has no effects when a book is private.'
		           ])
		           ->end();
		
		$formMapper
			->with('General')
			->add('parent', OrgAwareCategorySelectorType::class, [
				'organisation'  => $this->getCurrentOrganisation(),
				'category'      => $this->getSubject() ?: null,
				'model_manager' => $this->getModelManager(),
				'class'         => $this->getClass(),
				'required'      => true,
				'context'       => $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository(Context::class)->find(Context::DEFAULT_CONTEXT),
				// $this->getSubject()->getContext(),
				'btn_add'       => false
			])
			->end();

//		$keys = $formMapper->keys();
//		$key  = array_pop($keys);
//		array_unshift($keys, $key);
//		$formMapper->reorder($keys);
	}
}
