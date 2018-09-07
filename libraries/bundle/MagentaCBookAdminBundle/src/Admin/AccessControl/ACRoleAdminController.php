<?php


namespace Magenta\Bundle\CBookAdminBundle\Admin\AccessControl;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class ACRoleAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/AccessControl/ACRole/CRUD/list.html.twig');
        return parent::listAction();
    }
}