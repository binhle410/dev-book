<?php


namespace Magenta\Bundle\CBookAdminBundle\Admin\Media;


use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;

class GalleryHasMediaAdminController extends BaseCRUDAdminController
{
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Media/GalleryHasMedia/CRUD/list.html.twig');
        return parent::listAction();
    }
}