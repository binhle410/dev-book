<?php


namespace Magenta\Bundle\CBookAdminBundle\Admin\System\AccessControl;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class ACRoleAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Children/System/AccessControl/ACRole/CRUD/list.html.twig');
        return parent::listAction();
    }
}
