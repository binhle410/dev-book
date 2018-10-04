<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Person;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PersonAdmin extends BaseAdmin
{

    public function isGranted($name, $object = null)
    {
        return $this->isAdmin();
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

    protected function configureFormFields(FormMapper $form)
    {
        parent::configureFormFields($form);
        $form->add('name');
        $form->add('email', null, ['required' => false]);
        $form->add('user.plainPassword', TextType::class, ['required' => false, 'label' => 'form.label_password']);
        $form->add('user.adminOrganisations', ModelType::class, [
            'help' => 'This user is the root admin of the selected organisation(s)',
            'required' => false,
            'label' => 'form.label_organisation',
            'class' => Organisation::class,
            'multiple' => true,
            'property' => 'name',
            'btn_add' => false
        ]);
    }

    protected function configureListFields(ListMapper $list)
    {
        parent::configureListFields($list);
        $list->addIdentifier('name');
        $list->add('email');
    }

    /**
     * @param Person $object
     */
    public function preValidate($object)
    {
    }

}

