<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification\CategoryItem;

use Bean\Component\Thing\Model\Thing;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminControllerTrait;
use Magenta\Bundle\CBookAdminBundle\Admin\Book\BookAdmin;
use Magenta\Bundle\CBookAdminBundle\Admin\Classification\CategoryItemAdmin;
use Magenta\Bundle\CBookAdminBundle\Admin\Classification\CategoryItemAdminController;
use Magenta\Bundle\CBookAdminBundle\Service\Organisation\OrganisationService;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\BookCategoryItem;
use Magenta\Bundle\CBookModelBundle\Service\ServiceContext;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\CategoryItemContainerInterface;

class BookCategoryItemAdminController extends CategoryItemAdminController {
	/** @var BookCategoryItemAdmin $admin */
	protected $admin;
	
	public function createItem(): CategoryItemContainerInterface {
		return new Book();
	}
	
	public function createCategoryItem(): CategoryItem {
		return new BookCategoryItem();
	}
	
	public function createResponse(CategoryItem $item): Response {
		return new RedirectResponse($this->get(BookAdmin::class)->generateUrl('show', [ 'id' => $item->getItem()->getId() ]));
	}
	
}
