<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Organisation;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class OrganisationAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Organisation/Organisation/CRUD/list.html.twig');
        return parent::listAction();
    }
}