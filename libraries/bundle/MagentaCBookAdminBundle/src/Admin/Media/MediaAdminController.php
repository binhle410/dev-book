<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Media;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Symfony\Component\HttpFoundation\Request;

class MediaAdminController extends \Sonata\MediaBundle\Controller\MediaAdminController
{
    public function listAction(Request $request = null)
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Media/Media/CRUD/list.html.twig');
        return parent::listAction($request);
    }
}
