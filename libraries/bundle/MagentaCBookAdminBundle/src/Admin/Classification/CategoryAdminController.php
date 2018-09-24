<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminControllerTrait;
use Magenta\Bundle\CBookAdminBundle\Service\Organisation\OrganisationService;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Magenta\Bundle\CBookModelBundle\Service\ServiceContext;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CategoryAdminController extends \Sonata\ClassificationBundle\Controller\CategoryAdminController {
	/** @var CategoryAdmin $admin */
	protected $admin;
	
	use BaseCRUDAdminControllerTrait;
	
	/**
	 * @param Category $object
	 *
	 * @return RedirectResponse
	 */
	protected function redirectTo($object) {
		$request = $this->getRequest();
		
		$url = false;
		
		$parentId = 0;
		
		if( ! empty($parent = $object->getParent())) {
			$parentId = $parent->getId();
		}
		
		if(null !== $request->get('btn_update_and_list')) {
			return new RedirectResponse($this->admin->generateUrl('tree', array_merge($request->query->all(), [
				'hide_context' => 1,
				'parent'       => $object->getId()
			])));
		}
		if(null !== $request->get('btn_create_and_list')) {
			return new RedirectResponse($this->admin->generateUrl('tree', array_merge($request->query->all(), [
				'hide_context' => 1,
				'parent'       => $object->getId()
			])));
		}
		
		if('DELETE' === $this->getRestMethod()) {
			if( ! empty($parentId)) {
				return new RedirectResponse($this->admin->generateUrl('tree', array_merge($request->query->all(), [
					'hide_context' => 1,
					'parent'       => $parentId
				])));
			} else {
				return new RedirectResponse($this->admin->generateUrl('tree', array_merge($request->query->all(), [
					'hide_context' => 1
				])));
			}
		}
		
		return parent::redirectTo($object);
	}
	
	public function createCategoryAction(Request $request) {
		$categoryId = $request->request->getInt('category-id');
		$name       = $request->request->get('item-name');
		
		$registry       = $this->getDoctrine();
		$catRepo        = $registry->getRepository(Category::class);
		$cat            = $catRepo->find($categoryId);
		$defaultContext = $registry->getRepository(Context::class)->find(Context::DEFAULT_CONTEXT);
		$manager        = $this->get('doctrine.orm.default_entity_manager');
		
		if($request->isMethod('post')) {
			$cat = new Category();
			$cat->setName($name);
			
			$context = new ServiceContext();
			$context->setType(ServiceContext::TYPE_ADMIN_CLASS);
			$context->setAttribute('parent', $this->admin->getParent());
			
			$cat->setOrganisation($this->get(OrganisationService::class)->getCurrentOrganisation($context));
			$cat->setContext($defaultContext);
			
			if( ! empty($categoryId)) {
				/** Category $parentCategory */
				if( ! empty($parentCategory = $catRepo->find($categoryId))) {
					$parentCategory->addChild($cat);
					$cat->setParent($parentCategory);
					$manager->persist($parentCategory);
				} else {
					/** @var Category $root */
					$root = $this->get('sonata.classification.manager.category')->findOneBy([
						'context' => Context::DEFAULT_CONTEXT,
						'parent'  => null,
						'enabled' => true
					]);
					$root->addChild($cat);
					$cat->setParent($root);
				}
			}
			
			$manager = $this->get('doctrine.orm.default_entity_manager');
			$manager->persist($cat);
			$manager->flush();
			
		}
		
		return new RedirectResponse($this->admin->generateUrl('tree', [ 'id' => $categoryId ]));
	}
	
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
		
		$mediaFormBuilder = $this->createFormBuilder();
		$mediaFormBuilder->add('media', MediaType::class, array(
			'provider' => 'sonata.media.provider.file',
			'context'  => 'default'
		));
		
		$mediaForm = $mediaFormBuilder->getForm();
		$data      = null;
		
		
		return $this->renderWithExtraParams($this->get('sonata.classification.admin.category.template_registry')->getTemplate('tree'), [
			'action'             => 'tree',
			'newItemForm'          => $mediaForm->createView(),
			'parent'             => $parent,
			'current_categories' => $currentCategories,
			'root_categories'    => $rootCategoriesSplitByContexts,
			'current_context'    => $currentContext,
			'form'               => $formView,
			'csrf_token'         => $this->getCsrfToken('sonata.batch'),
		]);
	}
}
