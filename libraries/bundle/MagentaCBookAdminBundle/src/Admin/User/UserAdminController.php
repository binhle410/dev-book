<?php
namespace Magenta\Bundle\CBookAdminBundle\Admin\User;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Magenta\Bundle\CBookModelBundle\Service\User\UserService;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class UserAdminController extends BaseCRUDAdminController {
    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/User/User/CRUD/list.html.twig');
        return parent::listAction();
    }
}