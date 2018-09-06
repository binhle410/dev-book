<?php
/**
 * Created by PhpStorm.
 * User: THU_HUYEN
 * Date: 9/6/2018
 * Time: 5:42 PM
 */

namespace Magenta\Bundle\CBookAdminBundle\Admin\Media;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class GalleryAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Media/Gallery/CRUD/list.html.twig');
        return parent::listAction();
    }
}