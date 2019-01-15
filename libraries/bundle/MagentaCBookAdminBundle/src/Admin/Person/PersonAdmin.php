<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Person;

use Doctrine\ORM\QueryBuilder;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PersonAdmin extends BaseAdmin
{
    public function isGranted($name, $object = null)
    {
//        return parent::isGranted($name, $object);
        if ($this->isAdmin()) {
            return true;
        }

        if (empty($object)) {
            return false;
        }

        if (!is_array($name) && 'EDIT' === strtoupper($name)) {
            return $this->getLoggedInUser()->getPerson() === $object;
        }

        return false;
    }

    public function getNewInstance()
    {
        /** @var Person $person */
        $person = parent::getNewInstance();
        if (empty($person->getUser())) {
            $person->initiateUser(false);
        }

        return $person;
    }

    public function createQuery($context = 'list')
    {
        /**
         * @var \Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery
         */
        $query = parent::createQuery($context);

        if ($this->isAdmin()) {
            return $query;
        } else {
            /** @var QueryBuilder $qb */
            $qb = $query->getQueryBuilder();
            $expr = $qb->expr();
            $rootAlias = $qb->getRootAliases()[0];
            $qb
                ->join($rootAlias.'.individualMembers', 'individual')
                ->join('individual.organization', 'organization');
            $qb->andWhere($expr->eq('organization.id', $this->getCurrentOrganisation()->getId()));
        }

        return $query;
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('name')
            ->add('familyName')
            ->add('givenName')
            ->add('email')
        ;
    }

    protected function configureFormFields(FormMapper $form)
    {
        parent::configureFormFields($form);
        $form->add('givenName');
        $form->add('familyName');
        $form->add('email', null, ['required' => false]);
        $form->add('idNumber', null, ['required' => true]);
        $form->add('user.username', TextType::class, ['required' => true, 'label' => 'form.label_username']);
        $form->add('user.plainPassword', TextType::class, ['required' => false, 'label' => 'form.label_password']);
        $form->add('user.adminOrganisations', ModelType::class, [
            'help' => 'This user is the root admin of the selected organisation(s)',
            'required' => false,
            'label' => 'form.label_organisation',
            'class' => Organisation::class,
            'multiple' => true,
            'property' => 'name',
            'btn_add' => false,
        ]);
    }

    protected function configureListFields(ListMapper $list)
    {
        parent::configureListFields($list);
        $list->addIdentifier('name');
        $list->add('email')
        ;
        $list->add('_action', 'actions', [
                'actions' => [
//					'impersonate' => array( 'template' => 'admin/user/list__action__impersonate.html.twig' ),
                    'impersontate' => ['template' => '@MagentaCBookAdmin/Admin/Person/Person/Action/list__action__impersonate.html.twig'],
                    'edit' => [],
                    'delete' => [],

//                ,
//                    'view_description' => array('template' => '::admin/product/description.html.twig')
//                ,
//                    'view_tos' => array('template' => '::admin/product/tos.html.twig')
                ],
            ]
        );
            //
    }

    /**
     * @param Person $object
     */
    public function preValidate($object)
    {
    }
}
