<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Book;

use Bean\Component\Book\Model\Chapter;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
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

class ChapterAdmin extends BaseAdmin {
	
	const CHILDREN = [ SubChapterAdmin::class => 'parentChapter' ];
	
	protected $action;
	
	protected $datagridValues = array(
		// display the first page (default = 1)
//        '_page' => 1,
		// reverse order (default = 'ASC')
		'_sort_order' => 'DESC',
		// name of the ordered field (default = the model's id field, if any)
		'_sort_by'    => 'updatedAt',
	);
	
	public function getBook() {
		return $this->subject->getBook();
	}
	
	public function getCurrentChapter() {
		return $this->subject;
	}
	
	public function getNewInstance() {
		/** @var User $object */
		$object = parent::getNewInstance();
		
		return $object;
	}
	
	protected function getChildrenConst() {
		return self::CHILDREN;
	}
	
	public function toString($object) {
		return $object instanceof Chapter
			? $object->getName()
			: 'Section'; // shown in the breadcrumb on the create view
	}
	
	public function isGranted($name, $object = null) {
		if($name === 'LIST') {
			return false;
		}
		
		return parent::isGranted($name, $object);
	}
	
	public function createQuery($context = 'list') {
		/** @var ProxyQuery $query */
		$query = parent::createQuery($context);
		/** @var QueryBuilder $qb */
		$qb  = $query->getQueryBuilder();
		$exp = $qb->expr();
		
		if( ! empty($this->getChildrenConst())) {
			$qb->andWhere($exp->isNull($qb->getRootAliases()[0] . '.parentChapter'));
		}

//        $query->andWhere()
		
		return $query;
	}
	
	public function configureRoutes(RouteCollection $collection) {
		parent::configureRoutes($collection);
		$collection->add('contentEdit', $this->getRouterIdParameter() . '/edit-content');
		$collection->add('move', $this->getRouterIdParameter() . '/move');
		$collection->add('createChapter', 'new-instance');
		$collection->add('deleteChapter', $this->getRouterIdParameter() . '/delete-instance');
	}
	
	protected function configureShowFields(ShowMapper $showMapper) {
	
	}
	
	protected function getCustomActionListField() {
		$actionArray['subchapters'] = array( 'template' => '@MagentaCBookAdmin/Admin/Book/Children/Chapter/Action/list__action__subChapters.html.twig' );
		
		return $actionArray;
	}
	
	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper) {
		$actionArray = $this->getCustomActionListField();
		$listMapper->add('_action', 'actions', [
				'actions' => array_merge($actionArray, array(
//					'impersonate' => array( 'template' => 'admin/user/list__action__impersonate.html.twig' ),
					'edit'   => array(),
					'delete' => array(),

//                ,
//                    'view_description' => array('template' => '::admin/product/description.html.twig')
//                ,
//                    'view_tos' => array('template' => '::admin/product/tos.html.twig')
				))
			]
		);
		$listMapper
			->addIdentifier('name')
			->add('position', null, [ 'editable' => true ])
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
			->with('Content', [ 'class' => 'col-md-6' ])->end();
		
		
		$formMapper
			->with('General')
//                ->add('username')
			->add('name', null, [ 'label' => 'list.label_name' ])
			->add('position')
			->end();
		
		$formMapper
			->with('Content')
			->add('text', CKEditorType::class, []);
		
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
