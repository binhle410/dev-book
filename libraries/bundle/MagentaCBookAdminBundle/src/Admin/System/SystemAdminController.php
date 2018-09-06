<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\System;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class SystemAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/System/System/CRUD/list.html.twig');
        return parent::listAction();
    }
}