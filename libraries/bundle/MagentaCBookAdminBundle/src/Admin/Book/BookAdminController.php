<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Book;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookAdminController extends BaseCRUDAdminController {
	
	/** @var BookAdmin $admin */
	protected $admin;
	
	public function renderWithExtraParams($view, array $parameters = [], Response $response = null) {
		if($parameters['action'] === 'show') {
			/** @var Book $book */
			$book                             = $this->admin->getSubject();
			$accessCode                       = "1";
			$employeeCode                     = "1";
			$parameters['base_book_template'] = '@MagentaCBookAdmin/standard_layout.html.twig';
			$parameters['book']               = $book;
			$parameters['mainContentItem']    = $book;
			$parameters['subContentItems']    = $book->getRootChapters();
			$parameters['accessCode']         = $accessCode;
			$parameters['employeeCode']       = $employeeCode;
		}
		
		return parent::renderWithExtraParams($view, $parameters, $response);
	}
	
	public function showAction($id = null) {
		$this->admin->setTemplate('show', '@MagentaCBookAdmin/Admin/Book/Book/CRUD/show.html.twig');
		
		return parent::showAction($id);
	}
	
	public function listAction() {
		$this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Book/Book/CRUD/list.html.twig');
		
		return parent::listAction();
	}
}
