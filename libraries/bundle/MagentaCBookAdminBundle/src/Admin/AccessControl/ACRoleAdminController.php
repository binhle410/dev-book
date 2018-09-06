<?php
/**
 * Created by PhpStorm.
 * User: THU_HUYEN
 * Date: 9/6/2018
 * Time: 5:41 PM
 */

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