<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminControllerTrait;
use Magenta\Bundle\CBookAdminBundle\Service\Organisation\OrganisationService;
use Magenta\Bundle\CBookModelBundle\Service\ServiceContext;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoryAdminController extends \Sonata\ClassificationBundle\Controller\CategoryAdminController {
	/** @var CategoryAdmin $admin */
	protected $admin;
	
	use BaseCRUDAdminControllerTrait;
	
	public function listAction(Request $request = null) {
		if( ! $request->get('filter') && ! $request->get('filters')) {
			return new RedirectResponse($this->admin->generateUrl('tree', array_merge($request->query->all(), [ 'hide_context' => 1 ])));
		}
		
		return parent::listAction($request);
	}
	
	/**
	 * copied from parent
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function treeAction(Request $request) {
		$this->admin->setTemplate('tree', '@MagentaCBookAdmin/Admin/Classification/Category/CRUD/tree.html.twig');
		
		$categoryManager = $this->get('sonata.classification.manager.category');
		$currentContext  = false;
		if($context = $request->get('context')) {
			$currentContext = $this->get('sonata.classification.manager.context')->find($context);
		}
		
		$context = new ServiceContext();
		$context->setType(ServiceContext::TYPE_ADMIN_CLASS);
		$context->setAttribute('parent', $this->admin->getParent());
		// root categories inside the current context
		$currentCategories = [];
		$parent            = null;
// all root categories.
		$rootCategoriesSplitByContexts = $categoryManager->getRootCategoriesSplitByContexts(false, $this->get(OrganisationService::class)->getCurrentOrganisation($context));
		
		
		if( ! $currentContext && ! empty($rootCategoriesSplitByContexts)) {
			$currentCategories = current($rootCategoriesSplitByContexts);
			$currentContext    = current($currentCategories)->getContext();
		} else {
			foreach($rootCategoriesSplitByContexts as $contextId => $contextCategories) {
				if($currentContext->getId() != $contextId) {
					continue;
				}
				
				foreach($contextCategories as $category) {
					if($currentContext->getId() != $category->getContext()->getId()) {
						continue;
					}
					
					$currentCategories[] = $category;
				}
			}
		}
		
		if(empty($parentId = $request->get('parent', 0))) {
		} else {
			$categoryRepo = $this->getDoctrine()->getRepository(Category::class);
			if( ! empty($parent = $categoryRepo->find($parentId))) {
			
			}
		}
		
		
		$datagrid = $this->admin->getDatagrid();
		
		if($this->admin->getPersistentParameter('context')) {
			$datagrid->setValue('context', null, $this->admin->getPersistentParameter('context'));
		}
		
		$formView = $datagrid->getForm()->createView();
		
		$this->setFormTheme($formView, $this->admin->getFilterTheme());
		
		return $this->renderWithExtraParams($this->admin->getTemplate('tree'), [
			'action'             => 'tree',
			'parent'             => $parent,
			'current_categories' => $currentCategories,
			'root_categories'    => $rootCategoriesSplitByContexts,
			'current_context'    => $currentContext,
			'form'               => $formView,
			'csrf_token'         => $this->getCsrfToken('sonata.batch'),
		]);
	}
}
