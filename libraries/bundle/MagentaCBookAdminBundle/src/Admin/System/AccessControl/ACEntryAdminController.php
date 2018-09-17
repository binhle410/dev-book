<?php


namespace Magenta\Bundle\CBookAdminBundle\Admin\System\AccessControl;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class ACEntryAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/AccessControl/ACEntry/CRUD/list.html.twig');
        return parent::listAction();
    }
}
