<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Organisation;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Magenta\Bundle\CBookModelBundle\Service\User\UserManager;
use Magenta\Bundle\CBookModelBundle\Service\User\UserManagerInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrganisationAdmin extends BaseAdmin {
	protected $action;
	
	protected $datagridValues = array(
		// display the first page (default = 1)
//        '_page' => 1,
		// reverse order (default = 'ASC')
		'_sort_order' => 'DESC',
		// name of the ordered field (default = the model's id field, if any)
		'_sort_by'    => 'updatedAt',
	);
	
	public function getNewInstance() {
		/** @var User $object */
		$object = parent::getNewInstance();
		
		return $object;
	}
	
	/**
	 * @param string $name
	 * @param User   $object
	 */
	public function isGranted($name, $object = null) {
		$container = $this->getConfigurationPool()->getContainer();
		$isAdmin   = $container->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
//        $pos = $container->get(UserService::class)->getPosition();
		if(in_array($name, [ 'CREATE', 'DELETE', 'LIST' ])) {
			return $isAdmin;
		}
		if($name === 'EDIT') {
			if($isAdmin) {
				return true;
			}
			if( ! empty($object) && $object->getId() === $container->get(UserService::class)->getUser()->getId()) {
				return true;
			}
			
			return false;
		}
//        if (empty($isAdmin)) {
//            return false;
//        }
		
		return parent::isGranted($name, $object);
	}
	
	public function toString($object) {
		return $object instanceof User
			? $object->getEmail()
			: 'Talent'; // shown in the breadcrumb on the create view
	}
	
	public function createQuery($context = 'list') {
		/** @var ProxyQueryInterface $query */
		$query = parent::createQuery($context);
		if(empty($this->getParentFieldDescription())) {
//            $this->filterQueryByPosition($query, 'position', '', '');
		}

//        $query->andWhere()
		
		return $query;
	}
	
	public function configureRoutes(RouteCollection $collection) {
		parent::configureRoutes($collection);
//		$collection->add('show_user_profile', $this->getRouterIdParameter() . '/show-user-profile');
		
	}
	
	public function getTemplate($name) {
		return parent::getTemplate($name);
	}
	
	protected function configureShowFields(ShowMapper $showMapper) {
	
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->add('_action', 'actions', [
				'actions' => array(
//					'impersonate' => array( 'template' => 'admin/user/list__action__impersonate.html.twig' ),
					'edit'   => array(),
					'delete' => array(),
//					'send_evoucher' => array( 'template' => '::admin/employer/employee/list__action_send_evoucher.html.twig' )

//                ,
//                    'view_description' => array('template' => '::admin/product/description.html.twig')
//                ,
//                    'view_tos' => array('template' => '::admin/product/tos.html.twig')
				)
			]
		);
		$listMapper
			->addIdentifier('name')
			->add('createdAt');
		
		if($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
			$listMapper
				->add('impersonating', 'string', [ 'template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig' ]);
		}
		
		$listMapper->remove('impersonating');
		$listMapper->remove('groups');
//		$listMapper->add('positions', null, [ 'template' => '::admin/user/list__field_positions.html.twig' ]);
	}
	
	protected function configureFormFields(FormMapper $formMapper) {
		
		$formMapper
			->with('General', [ 'class' => 'col-md-6' ])->end()
			->with('Profile', [ 'class' => 'col-md-6' ])->end()
			;
		
		
		$formMapper
			->with('General')
//                ->add('username')
			->add('name', null, [ 'label' => 'list.label_name' ])
//                ->add('admin')
			->end();
			
		
		$formMapper->end();
	}
	
	
	/**
	 * @param User $object
	 */
	public function prePersist($object) {
		parent::prePersist($object);
		if( ! $object->isEnabled()) {
			$object->setEnabled(true);
		}
	}
	
	/**
	 * @param User $object
	 */
	public function preUpdate($object) {
		
		$this->getUserManager()->updateCanonicalFields($object);
		$this->getUserManager()->updatePassword($object);
		
		if( ! $object->isEnabled()) {
			$object->setEnabled(true);
		}
	}
	
	///////////////////////////////////
	///
	///
	///
	///////////////////////////////////
	/**
	 * @var UserManagerInterface
	 */
	protected $userManager;
	
	
	/**
	 * {@inheritdoc}
	 */
	protected function configureDatagridFilters(DatagridMapper $filterMapper) {
		$filterMapper
			->add('id')
			->add('name')//			->add('locked')
		;
//			->add('groups')
//		;
	}
	
	
}