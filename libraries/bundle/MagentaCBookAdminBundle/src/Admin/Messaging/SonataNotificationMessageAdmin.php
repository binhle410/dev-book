<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Messaging;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseAdmin;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\NotificationBundle\Admin\MessageAdmin;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SonataNotificationMessageAdmin extends MessageAdmin
{

    public function isGranted($name, $object = null)
    {
        return $this->getConfigurationPool()->getContainer()->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN');
    }

}

