<?php
/**
 * Created by PhpStorm.
 * User: THU_HUYEN
 * Date: 9/6/2018
 * Time: 5:44 PM
 */

namespace Magenta\Bundle\CBookAdminBundle\Admin\Media;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class MediaAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Media/Media/CRUD/list.html.twig');
        return parent::listAction();
    }
}