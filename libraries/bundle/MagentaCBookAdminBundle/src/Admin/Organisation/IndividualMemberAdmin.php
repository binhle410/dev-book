<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Organisation;

use Doctrine\ORM\EntityRepository;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualGroup;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACRole;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
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

use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IndividualMemberAdmin extends BaseAdmin {
	
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
		/** @var IndividualMember $object */
		$object = parent::getNewInstance();
		if(empty($object->getPerson())) {
			$object->setPerson(new Person());
		}
		
		if(empty($user = $object->getPerson()->getUser())) {
			$object->getPerson()->setUser(new User());
		}
		
		return $object;
	}
	
	/**
	 * @param string           $name
	 * @param IndividualMember $object
	 */
	public function isGranted($name, $object = null) {
		return parent::isGranted($name, $object);
	}
	
	public function toString($object) {
		return $object instanceof IndividualMember
			? $object->getPerson()->getName()
			: 'IndividualMember'; // shown in the breadcrumb on the create view
	}
	
	public function createQuery($context = 'list') {
		/** @var ProxyQueryInterface $query */
		$query = parent::createQuery($context);
		if(empty($this->getParentFieldDescription())) {
//            $this->filterQueryByPosition($query, 'position', '', '');
		}
		/** @var Expr $expr */
		$expr = $query->expr();
		/** @var QueryBuilder $qb */
		$qb        = $query->getQueryBuilder();
		$rootAlias = $qb->getRootAliases()[0];
		
		//        $query->andWhere()
		
		{
			return $query;
		}
	}
	
	public function getPersistentParameters() {
		$parameters = parent::getPersistentParameters();
		if( ! $this->hasRequest()) {
			return $parameters;
		}
		
		return array_merge($parameters, array(
			'organisation' => $this->getCurrentOrganisation(false)->getId()
		));
	}
	
	public function configureRoutes(RouteCollection $collection) {
		parent::configureRoutes($collection);
//		$collection->add('show_user_profile', $this->getRouterIdParameter() . '/show-user-profile');
	
	}
	
	public function getTemplate($name) {
		$_name = strtoupper($name);
		if($_name === 'LIST') {
		}
		
		return parent::getTemplate($name);
	}
	
	protected function configureShowFields(ShowMapper $showMapper) {
		$showMapper
			->with('form_group.member_details', [ 'class' => 'col-md-6' ])
			->add('name', null, [ 'label' => 'form.label_name' ])
			->add('email', null, [ 'label' => 'form.label_email' ])
			->add('homeAddress', null, [ 'label' => 'form.label_address' ])
			->add('homePostalCode', null, [ 'label' => 'form.label_postal_code' ])
			->end()
			->with('form_group.member_groups', [ 'class' => 'col-md-6' ])
			->add('warranties', null, [
				'label'               => false,
				'associated_property' => 'id',
				'template'            => '@MagentaSIndividualMemberAdmin/Admin/IndividualMember/IndividualMember/CRUD/Association/show_one_to_many.html.twig'
			])
			->end();
		
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->add('_action', 'actions', [
				'actions' => array(
					'show'   => array(),
					'edit'   => array(),
					'delete' => array(),
//					'send_evoucher' => array( 'template' => '::admin/employer/employee/list__action_send_evoucher.html.twig' )

//                ,
//                    'view_description' => array('template' => '::admin/product/description.html.twig')
//                ,
//                    'view_tos' => array('template' => '::admin/product/tos.html.twig')
				),
				'label'   => 'form.label_action'
			]
		);
		
		$listMapper
			->add('person.name', null, [ 'editable' => true, 'label' => 'form.label_name' ])
			->add('email', null, [ 'editable' => true, 'label' => 'form.label_email' ])
//			->add('role', null, [
//				'editable'            => true,
//				'label'               => 'form.label_role',
//				'associated_property' => 'name'
//			])
			->add('enabled', null, [ 'editable' => true, 'label' => 'form.label_enabled' ]);
	}
	
	protected function configureFormFields(FormMapper $formMapper) {
		/** @var ProxyQuery $productQuery */
		$acroleQuery = $this->getFilterByOrganisationQueryForModel(ACRole::class);
		
		$c = $this->getConfigurationPool()->getContainer();
		
		$passwordRequired = empty($this->subject);
		
		$formMapper
			->with('form_group.user_details', [ 'class' => 'col-md-6' ]);
		$formMapper
			->add('person.name', null, [ 'label' => 'form.label_name' ])
            ->add('person.telephone', null, [ 'label' => 'form.label_telephone' ])
            ->add('person.idNumber', null, [ 'label' => 'form.label_id_number' ])
            ->add('person.birthDate', DatePickerType::class, [
                'label' => 'form.label_dob',
                'required'              => true,
                'format'                => 'dd-MM-yyyy',
                'placeholder'           => 'dd-mm-yyyy',
                'datepicker_use_button' => false
                ])
			->add('email', null, [
				'required' => false,
				'label'    => 'form.label_email'
			])
			->add('person.user.plainPassword', TextType::class, [
				'label'    => 'form.label_password',
				'required' => $passwordRequired
			])
			->add('contactable')
//			->add('role', ModelType::class, [
//				'label'    => 'form.label_role',
//				'btn_add'  => false,
//				'property' => 'name',
//				'query'    => $acroleQuery
//
//			])
			->add('enabled');
		$formMapper->end();
		
		$groupQuery = $this->getFilterByOrganisationQueryForModel(IndividualGroup::class);
		$formMapper
			->with('form_group.groups', [ 'class' => 'col-md-6' ]);
		$formMapper->add('groups', ModelType::class, [
			'label'    => false,
			'query'    => $groupQuery,
			'class'    => IndividualGroup::class,
			'multiple' => true,
			'property' => 'name'
		]);
		$formMapper->end();
	}
	
	/** @param IndividualMember $object */
	public function preValidate(
		$object
	) {
		parent::preValidate($object);
		/** @var Person $p */
		if( ! empty($p = $object->getPerson())) {
			$u = $p->getUser();
			if( ! empty($u)) {
				if( ! empty($p->getEmail()) && $p->getEmail() !== $object->getEmail()) {
					$u->setPlainPassword(null);
				}
				if( ! empty($u->getPlainPassword()) && ! empty($u->getId())) {
//					$manager = $this->getConfigurationPool()->getContainer()->get('doctrine.orm.default_entity_manager');
				}
			}
		}
		$object->setOrganization($this->getCurrentOrganisation());
	}
	
	/**
	 * @param IndividualMember $object
	 */
	public function prePersist($object) {
		parent::prePersist($object);
		if( ! $object->isEnabled()) {
			$object->setEnabled(true);
		}
	}
	
	/**
	 * @param IndividualMember $object
	 */
	public function preUpdate($object) {
		parent::preUpdate($object);
	}
	
	///////////////////////////////////
	///
	///
	///
	///////////////////////////////////
	
	/**
	 * {@inheritdoc}
	 */
	protected function configureDatagridFilters(DatagridMapper $filterMapper) {
		$filterMapper
			->add('id')
			->add('person.name')//			->add('locked')
		;
		parent::configureDatagridFilters($filterMapper);
	}
	
	
}
