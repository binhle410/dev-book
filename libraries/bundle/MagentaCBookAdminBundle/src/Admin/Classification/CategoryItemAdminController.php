<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Classification;

use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\CategoryItemContainerInterface;
use Bean\Component\Thing\Model\Thing;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminControllerTrait;
use Magenta\Bundle\CBookAdminBundle\Admin\Book\BookAdmin;
use Magenta\Bundle\CBookAdminBundle\Service\Organisation\OrganisationService;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;
use Magenta\Bundle\CBookModelBundle\Service\ServiceContext;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class CategoryItemAdminController extends BaseCRUDAdminController
{
    /** @var CategoryItemAdmin $admin */
    protected $admin;

    public function deleteAction($id)
    {
        $this->admin->setTemplate('delete', '@MagentaCBookAdmin/Admin/Classification/CategoryItem/CRUD/delete.html.twig');
        return parent::deleteAction($id);
    }

    /**
     * @param CategoryItem $object
     * @return RedirectResponse|Response
     */
    protected function redirectTo($object)
    {
        $request = $this->getRequest();

        $url = false;

        if (null !== $request->get('btn_update_and_list')) {
            return $this->redirectToList();
        }
        if (null !== $request->get('btn_create_and_list')) {
            return $this->redirectToList();
        }

        if (null !== $request->get('btn_create_and_create')) {
            $params = [];
            if ($this->admin->hasActiveSubClass()) {
                $params['subclass'] = $request->get('subclass');
            }
            $url = $this->admin->generateUrl('create', $params);
        }

        if ('DELETE' === $this->getRestMethod()) {
//            return $this->redirectToList();
            return $this->createResponse($object);
        }

        if (!$url) {
            foreach (['edit', 'show'] as $route) {
                if ($this->admin->hasRoute($route) && $this->admin->hasAccess($route, $object)) {
                    $url = $this->admin->generateObjectUrl($route, $object);

                    break;
                }
            }
        }

        if (!$url) {
            return $this->redirectToList();
        }

        return new RedirectResponse($url);
    }

    public abstract function createItem(): CategoryItemContainerInterface;

    public abstract function createCategoryItem(): CategoryItem;

    public abstract function createResponse(CategoryItem $item): Response;

    public function newInstanceAction(Request $request)
    {
        $categoryId = $request->request->getInt('category-id');
        $name = $request->request->get('item-name');

        $registry = $this->getDoctrine();
        $catRepo = $registry->getRepository(Category::class);
        $cat = $catRepo->find($categoryId);

        if ($request->isMethod('post')) {
            $catItem = $this->createCategoryItem();
            $item = $this->createItem();
            $item->setName($name);

            $context = new ServiceContext();
            $context->setType(ServiceContext::TYPE_ADMIN_CLASS);
            $context->setAttribute('parent', $this->admin->getParent());

            $item->setOrganization($this->get(OrganisationService::class)->getCurrentOrganisation($context));
            if (method_exists($item, 'setCategory')) {
                $item->setCategory($cat);
            }

            $catItem->setItem($item);
            $item->addCategoryItem($catItem);
            $catItem->setCategory($cat);

            $manager = $this->get('doctrine.orm.default_entity_manager');
            $manager->persist($item);
            $manager->persist($catItem);
            $manager->flush();

        }

        return $this->createResponse($catItem);
    }
}
