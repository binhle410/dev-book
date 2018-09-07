<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Organisation;



use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class OrgBookAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Organisation/OrgBook/CRUD/list.html.twig');
        return parent::listAction();
    }
}