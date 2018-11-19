<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Messaging;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookAdminBundle\Admin\Book\BookAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageAdmin extends BaseAdmin
{
    const TEMPLATES = ['edit' => '@MagentaCBookAdmin/Admin/Messaging/Message/CRUD/edit.html.twig'];
    
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
        /** @var Message $object */
        $object = parent::getNewInstance();
        
        return $object;
    }
    
    /**
     * @param string $name
     * @param Message $object
     */
    public function isGranted($name, $object = null)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $isAdmin = $container->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
//        $pos = $container->get(MessageService::class)->getPosition();
        if (in_array($name, ['EDIT', 'DELETE'])) {
            if (empty($object)) {
                return $isAdmin;
            } else {
                return $object->getStatus() === Message::STATUS_DRAFT;
            }
        }
//        if (empty($isAdmin)) {
//            return false;
//        }
        
        return parent::isGranted($name, $object);
    }
    
    public function toString($object)
    {
        return $object instanceof Organisation
            ? $object->getName()
            : 'Message'; // shown in the breadcrumb on the create view
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
//		$collection->add('show_Message_profile', $this->getRouterIdParameter() . '/show-Message-profile');
    
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
                'actions' => array(
//					'impersonate' => array( 'template' => 'admin/Message/list__action__impersonate.html.twig' ),
//                    'cbook' => array('template' => '@MagentaCBookAdmin/Admin/Organisation/Action/list__action__cbooks.html.twig'),
                    'edit' => array(),
                    'delete' => array(),

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
        
        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
//            $listMapper
//                ->add('impersonating', 'string', ['template' => 'SonataMessageBundle:Admin:Field/impersonating.html.twig']);
        }
        
        $listMapper->remove('impersonating');
        $listMapper->remove('groups');
//		$listMapper->add('positions', null, [ 'template' => '::admin/Message/list__field_positions.html.twig' ]);
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, ['label' => 'form.label_title'])
            ->add('text', null, ['label' => 'form.label_body'])
            ->end();
    }
    
    /**
     * @param Message $object
     */
    public function preValidate($object)
    {
        parent::preValidate($object);
        $request = $this->getRequest();
        if (null !== $request->get('btn_publish')) {
            $object->deliver();
        }
    }
    
    /**
     * @param Message $object
     */
    public function prePersist($object)
    {
        parent::prePersist($object);
        if (!$object->isEnabled()) {
            $object->setEnabled(true);
        }
    }
    
    /**
     * @param Message $object
     */
    public function preUpdate($object)
    {
        if (!$object->isEnabled()) {
            $object->setEnabled(true);
        }
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
