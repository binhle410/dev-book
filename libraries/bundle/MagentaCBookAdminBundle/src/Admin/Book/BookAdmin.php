<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Book;

use Bean\Component\Book\Model\Book;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookAdminBundle\Admin\Classification\CategoryItem\BookCategoryItemAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\BookCategoryItem;
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

use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\CollectionType;
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\FormatterBundle\Form\Type\FormatterType;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Valid;

class BookAdmin extends BaseAdmin {
	
	const CHILDREN = [ ChapterAdmin::class => 'book' ];
	
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
	
	public function toString($object) {
		return $object instanceof Book
			? $object->getName()
			: 'Book'; // shown in the breadcrumb on the create view
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
	
	protected function configureShowFields(ShowMapper $showMapper) {
	
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper) {
		$listMapper->add('_action', 'actions', [
				'label'   => 'form.label_action',
				'actions' => array(
//					'impersonate' => array( 'template' => 'admin/user/list__action__impersonate.html.twig' ),
					'chapters' => array( 'template' => '@MagentaCBookAdmin/Admin/Book/Book/Action/list_chapters.html.twig' ),
					'edit'     => array(),
					'delete'   => array(),

//                ,
//                    'view_description' => array('template' => '::admin/product/description.html.twig')
//                ,
//                    'view_tos' => array('template' => '::admin/product/tos.html.twig')
				)
			]
		);
		$listMapper
			->addIdentifier('name', null, [ 'label' => 'form.label_name' ])
			->add('bookCategoryItems', null, [ 'label' => 'form.label_category' ])
			->add('createdAt', null, [ 'label' => 'form.label_created_at' ]);
		
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
			->with('Content', [ 'class' => 'col-md-6' ])->end();
		
		
		$formMapper
			->with('General')
//                ->add('username')
			->add('name', null, [ 'label' => 'form.label_name' ]);
//                ->add('admin')
		$formMapper->add('bookCategoryItems', CollectionType::class,
			array(
				'required'    => false,
				'constraints' => new Valid(),
				'label'       => 'form.label_category',
//					'btn_catalogue' => 'InterviewQuestionSetAdmin'
			), array(
				'edit'            => 'inline',
				'inline'          => 'table',
				'sortable'        => 'position',
				'link_parameters' => $this->getPersistentParameters(),
				'admin_code'      => BookCategoryItemAdmin::class,
				'delete'          => null,
			)
		);
		$formMapper->end();

//		$formMapper->with('Content');
//		$formMapper->add('text', CKEditorType::class, [
//		]);
//		$formMapper->add('text', SimpleFormatterType::class, [
//			'format' => 'richhtml',
//			'ckeditor_context' => 'default',
//			'ckeditor_image_format' => 'big',
//		]);
//		$formMapper->end();
		
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
