<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Organisation;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookAdminBundle\Admin\Book\BookAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualGroup;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
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
use Sonata\CoreBundle\Form\Type\DatePickerType;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IndividualGroupAdmin extends BaseAdmin
{
    
    const CHILDREN = [];
    const TEMPLATES = [
        'edit' => '@MagentaCBookAdmin/Admin/Organisation/IndividualGroup/CRUD/edit.html.twig',
    ];
    
    protected $action;
    
    protected $datagridValues = array(
        // display the first page (default = 1)
//        '_page' => 1,
        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',
        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'updatedAt',
    );
    
    public function getNewInstance()
    {
        /** @var IndividualGroup $object */
        $object = parent::getNewInstance();
        
        return $object;
    }
    
    /**
     * @param string $name
     * @param IndividualGroup $object
     */
    public function isGranted($name, $object = null)
    {
        return parent::isGranted($name, $object);
    }
    
    public function toString($object)
    {
        return $object instanceof Organisation
            ? $object->getName()
            : 'Organisation'; // shown in the breadcrumb on the create view
    }
    
    public function createQuery($context = 'list')
    {
        /** @var ProxyQueryInterface $query */
        $query = parent::createQuery($context);
        if (empty($this->getParentFieldDescription())) {
//            $this->filterQueryByPosition($query, 'position', '', '');
        }

//        $query->andWhere()
        
        return $query;
    }
    
    public function configureRoutes(RouteCollection $collection)
    {
        parent::configureRoutes($collection);
//		$collection->add('show_user_profile', $this->getRouterIdParameter() . '/show-user-profile');
    
    }
    
    public function getTemplate($name)
    {
        return parent::getTemplate($name);
    }
    
    protected function configureShowFields(ShowMapper $showMapper)
    {
    
    }
    
    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('_action', 'actions', [
                'label' => 'form.label_action',
                'actions' => array(
//					'impersonate' => array( 'template' => 'admin/user/list__action__impersonate.html.twig' ),
//					'cbook'  => array( 'template' => '@MagentaCBookAdmin/Admin/Organisation/Action/list__action__cbooks.html.twig' ),
//                    'edit' => array(),
                    'members' => array( 'template' => '@MagentaCBookAdmin/Admin/Organisation/IndividualGroup/Action/list__action__members.html.twig' ),
                    'delete' => array(),

//                ,
//                    'view_description' => array('template' => '::admin/product/description.html.twig')
//                ,
//                    'view_tos' => array('template' => '::admin/product/tos.html.twig')
                )
            ]
        );
        $listMapper
            ->add('name', null, ['label' => 'form.label_name', 'editable' => true])
            ->add('createdAt', null, ['label' => 'form.label_created_at']);
        
        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('impersonating', 'string', ['template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig']);
        }
        
        $listMapper->remove('impersonating');
        $listMapper->remove('groups');
//		$listMapper->add('positions', null, [ 'template' => '::admin/user/list__field_positions.html.twig' ]);
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        
        $formMapper
            ->with('General', ['class' => 'col-md-3'])->end();
        
        $formMapper
            ->with('General')
//                ->add('username')
            ->add('name', null, ['label' => 'form.label_name'])
//                ->add('admin')
            ->end();
        $formMapper->end();
    }
    
    
    /**
     * @param IndividualGroup $object
     */
    public function prePersist($object)
    {
        parent::prePersist($object);
        if (!$object->isEnabled()) {
            $object->setEnabled(true);
        }
    }
    
    /**
     * @param IndividualGroup $object
     */
    public function preUpdate($object)
    {
        if (!$object->isEnabled()) {
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
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('id')
            ->add('name')//			->add('locked')
        ;
//			->add('groups')
//		;
    }
    
    
}
