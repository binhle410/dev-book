<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Person;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class PersonAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Person/Person/CRUD/list.html.twig');
        return parent::listAction();
    }
}