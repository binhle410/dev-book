<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminControllerTrait;
use Magenta\Bundle\CBookAdminBundle\Service\Organisation\OrganisationService;
use Magenta\Bundle\CBookModelBundle\Service\ServiceContext;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryItemAdminController extends BaseCRUDAdminController {
	/** @var CategoryItemAdmin $admin */
	protected $admin;
	
	public function deleteAction($id) {
		$this->admin->setTemplate('delete', '@MagentaCBookAdmin/Admin/Classification/CategoryItem/CRUD/delete.html.twig');
		return parent::deleteAction($id);
	}
}
