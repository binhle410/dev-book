<?php


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