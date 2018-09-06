<?php
/**
 * Created by PhpStorm.
 * User: THU_HUYEN
 * Date: 9/6/2018
 * Time: 5:38 PM
 */

namespace Magenta\Bundle\CBookAdminBundle\Admin\AccessControl;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class ACEntryAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/AccessControl/ACEntry/CRUD/list.html.twig');
        return parent::listAction();
    }
}