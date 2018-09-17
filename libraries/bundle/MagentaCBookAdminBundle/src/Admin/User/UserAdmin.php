<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\User;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookAdminBundle\Form\Type\SecurityRolesType;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
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

class UserAdmin extends BaseAdmin {
	protected $action;
	
	protected $datagridValues = array(
		// display the first page (default = 1)
//        '_page' => 1,
		// reverse order (default = 'ASC')
		'_sort_order' => 'DESC',
		// name of the ordered field (default = the model's id field, if any)
		'_sort_by'    => 'updatedAt',
	);
	
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
			: 'User'; // shown in the breadcrumb on the create view
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
		$this->configureParentShowFields($showMapper);
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
			->addIdentifier('username')
			->add('email')
			->add('groups')
			->add('enabled', null, [ 'editable' => true ])
			->add('locked', null, [ 'editable' => true ])
			->add('createdAt');
		
		if($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
			$listMapper
				->add('impersonating', 'string', [ 'template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig' ]);
		}
		
		$listMapper->remove('impersonating');
		$listMapper->remove('groups');
//		$listMapper->add('positions', null, [ 'template' => '::admin/user/list__field_positions.html.twig' ]);
	}
	
	private function configureParentFormFields(FormMapper $formMapper) {
		
		// define group zoning
		$now = new \DateTime();
		
		$formMapper
//			->tab('User')
			->with('General')
			->add('username')
			->add('email')
			->add('plainPassword', TextType::class, [
				'required' => ( ! $this->getSubject() || is_null($this->getSubject()->getId())),
			])
			->add('person.birthDate', DatePickerType::class, [
				'years'       => range(1940, $now->format('Y')),
				'dp_min_date' => '01/01/1930',
				'dp_max_date' => $now->format('c'),
				'format'      => 'dd/MM/yyyy',
				
				'required' => false,
			])
			->add('person.givenName', null, [ 'required' => false ])
			->add('person.familyName', null, [ 'required' => false ])
//			->add('biography', TextType::class, [ 'required' => false ])
//			->add('gender', 'Sonata\UserBundle\Form\Type\UserGenderListType', [
//				'required'           => true,
//				'translation_domain' => $this->getTranslationDomain(),
//			])
//			->add('locale', 'locale', [ 'required' => false ])
//			->add('timezone', 'timezone', [ 'required' => false ])
//			->add('thanhVien.soDienThoai', null, [ 'required' => false ])

//			->with('Social')
//			->add('facebookUid', null, [ 'required' => false ])
//			->add('facebookName', null, [ 'required' => false ])
//			->add('twitterUid', null, [ 'required' => false ])
//			->add('twitterName', null, [ 'required' => false ])
//			->add('gplusUid', null, [ 'required' => false ])
//			->add('gplusName', null, [ 'required' => false ])
		
		;
		$formMapper->end();
		if($this->getSubject() && ! $this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
			$formMapper->with('Security');
			$formMapper
//				->add('locked', null, [ 'required' => false ])
//				->add('expired', null, [ 'required' => false ])
				->add('enabled', null, [ 'required' => false ])
//				->add('credentialsExpired', null, [ 'required' => false ])

//				->with('Groups')
//				->add('groups', 'sonata_type_model', [
//					'required' => false,
//					'expanded' => true,
//					'multiple' => true,
//				])
//				->end()
				->add('realRoles', SecurityRolesType::class, [
					'label'    => 'form.label_roles',
					'expanded' => true,
					'multiple' => true,
					'required' => false,
				])
			;
			$formMapper->end();
		}

//		$formMapper
//			->tab('Security')
//			->with('Keys')
//			->add('token', null, [ 'required' => false ])
//			->add('twoStepVerificationCode', null, [ 'required' => false ])
//			->end()
//			->end();
	}
	
	protected function configureFormFields(FormMapper $formMapper) {
		
		if($this->getConfigurationPool()->getContainer()->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
			$this->configureParentFormFields($formMapper);
		} else {
			$formMapper
				->with('Profile', [ 'class' => 'col-md-6' ])->end()
				->with('General', [ 'class' => 'col-md-6' ])->end();
			
			$formMapper
				->with('General')
//                ->add('username')
				->add('email', null, [ 'label' => 'list.label_email' ])
//                ->add('admin')
				->add('plainPassword', TextType::class, [
					'label'    => 'list.label_plain_password',
					'required' => ( ! $this->getSubject() || is_null($this->getSubject()->getId())),
				])
				->end()
				->with('Profile');
			
			if( ! empty($this->getConfigurationPool()->getContainer()->get(UserService::class)->getUser()->getThanhVien())) {
				$formMapper
					->add('thanhVien.lastname', null, [
						'required'           => false,
						'label'              => 'list.label_lastname',
						'translation_domain' => 'BinhLeAdmin'
					])
					->add('thanhVien.middlename', null, [
						'required'           => false,
						'label'              => 'list.label_middlename',
						'translation_domain' => $this->getTranslationDomain()
					])
					->add('thanhVien.firstname', null, [
						'required'           => false,
						'label'              => 'list.label_firstname',
						'translation_domain' => 'BinhLeAdmin'
					]);
				$formMapper->add('thanhVien.soDienThoai', null, array(
					'label'              => 'list.label_so_dien_thoai',
					'translation_domain' => 'BinhLeAdmin'
				))
				           ->add('thanhVien.soDienThoaiSecours', null, array(
					           'label'              => 'list.label_so_dien_thoai_secours',
					           'translation_domain' => 'BinhLeAdmin'
				           ))
				           ->add('thanhVien.diaChiThuongTru', null, array(
					           'label'              => 'list.label_dia_chi_thuong_tru',
					           'translation_domain' => 'BinhLeAdmin'
				           ))
				           ->add('thanhVien.diaChiTamTru', null, array(
					           'label'              => 'list.label_dia_chi_tam_tru',
					           'translation_domain' => 'BinhLeAdmin'
				           ));
			} else {
				$formMapper
					->add('lastname', null, [ 'required' => false ])
					->add('middlename', null, [ 'required' => false ])
					->add('firstname', null, [ 'required' => false ]);
			}
			
			$formMapper->end();
		}
		
	}
	
	public function getNewInstance() {
		/** @var User $object */
		$object = parent::getNewInstance();
		
		if(empty($object->getPerson())) {
			$object->setPerson(new Person());
		}
		
		return $object;
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
	public function getFormBuilder() {
		$this->formOptions['data_class'] = $this->getClass();
		
		$options                      = $this->formOptions;
		$options['validation_groups'] = ( ! $this->getSubject() || is_null($this->getSubject()->getId())) ? 'Registration' : 'Profile';
		
		$formBuilder = $this->getFormContractor()->getFormBuilder($this->getUniqid(), $options);
		
		$this->defineFormBuilder($formBuilder);
		
		return $formBuilder;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getExportFields() {
		// avoid security field to be exported
		return array_filter(parent::getExportFields(), function($v) {
			return ! in_array($v, [ 'password', 'salt' ]);
		});
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function configureDatagridFilters(DatagridMapper $filterMapper) {
		$filterMapper
			->add('id')
			->add('username')
//			->add('locked')
			->add('email');
//			->add('groups')
//		;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function configureParentShowFields(ShowMapper $showMapper) {
		$showMapper
			->with('General')
			->add('username')
			->add('email')
			->end()
			->with('Groups')
			->add('groups')
			->end()
			->with('Profile')
			->add('dateOfBirth')
			->add('firstname')
			->add('lastname')
			->add('website')
			->add('biography')
			->add('gender')
			->add('locale')
			->add('timezone')
			->add('phone')
			->end()
			->with('Social')
			->add('facebookUid')
			->add('facebookName')
			->add('twitterUid')
			->add('twitterName')
			->add('gplusUid')
			->add('gplusName')
			->end()
			->with('Security')
			->add('token')
			->add('twoStepVerificationCode')
			->end();
	}
	
	/**
	 * @return UserManagerInterface
	 */
	public function getUserManager() {
		if(empty($this->userManager)) {
			$this->userManager = $this->getConfigurationPool()->getContainer()->get('magenta_user.user_manager');
		}
		
		return $this->userManager;
	}
	
}
