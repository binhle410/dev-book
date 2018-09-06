<?php
/**
 * Created by PhpStorm.
 * User: THU_HUYEN
 * Date: 9/6/2018
 * Time: 11:55 AM
 */

namespace Magenta\Bundle\CBookAdminBundle\Admin\System;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class SystemModuleAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/System/SystemModule/CRUD/list.html.twig');
        return parent::listAction();
    }
}