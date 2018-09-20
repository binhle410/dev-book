<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Book;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookAdminController extends BaseCRUDAdminController {
	
	/** @var BookAdmin $book */
	protected $admin;
	
	public function contentEditAction(Request $request, $id = null) {
		$id = $request->get($this->admin->getIdParameter());
		
		$object = $this->admin->getObject($id);
		
		if( ! $object) {
			throw $this->createNotFoundException(sprintf('unable to find the book with id: %s', $id));
		}
		
		
		$field = $request->get('field', 'name');
		if($field === 'name') {
			$content = strip_tags($request->get('content'), '<strong><b><i><u>');
		} else {
			$content = $request->get('content');
		}
		$setter = 'set' . ucfirst($field);
		$object->{$setter}($content);
		
		$manager = $this->get('doctrine.orm.default_entity_manager');
		$manager->persist($object);
		$manager->flush();
		
		return new JsonResponse([ 'book content edited ' . $content ]);
	}
	
	public function renderWithExtraParams($view, array $parameters = [], Response $response = null) {
		$accessCode                       = "1";
		$employeeCode                     = "1";
		$parameters['base_book_template'] = '@MagentaCBookAdmin/standard_layout.html.twig';
		$parameters['book']               = $this->admin->getSubject();
		$parameters['accessCode']         = $accessCode;
		$parameters['employeeCode']       = $employeeCode;
		
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
