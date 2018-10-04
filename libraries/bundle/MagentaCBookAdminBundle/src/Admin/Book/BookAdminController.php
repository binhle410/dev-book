<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Book;

use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Magenta\Bundle\CBookAdminBundle\Service\Organisation\OrganisationService;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem\BookCategoryItem;
use Magenta\Bundle\CBookModelBundle\Service\ServiceContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookAdminController extends BaseCRUDAdminController
{

    /** @var BookAdmin $admin */
    protected $admin;

    public function renderWithExtraParams($view, array $parameters = [], Response $response = null)
    {
        if ($parameters['action'] === 'show') {
            /** @var Book $book */
            $book = $this->admin->getSubject();
            $orgSlug = "1";
            $accessCode = "1";
            $employeeCode = "1";
            $parameters['base_book_template'] = '@MagentaCBookAdmin/standard_layout.html.twig';
            $parameters['book'] = $book;
            $parameters['mainContentItem'] = $book;
            $parameters['subContentItems'] = $book->getRootChapters();
            $parameters['orgSlug'] = $orgSlug;
            $parameters['accessCode'] = $accessCode;
            $parameters['employeeCode'] = $employeeCode;
        }

        return parent::renderWithExtraParams($view, $parameters, $response);
    }

    public function publishAction(Request $request, $id = null)
    {
        $request = $this->getRequest();
        $id = $request->get($this->admin->getIdParameter());

        /** @var Book $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }

        if ($request->isMethod('get')) {
            return new RedirectResponse($this->get('router')->generate('admin_magenta_cbookmodel_book_book_show', ['id' => $object->getId()]));
        }

        if ($object->getStatus() !== Book::STATUS_DRAFT) {
            $this->addFlash('error', 'Not a Draft Version so it cannot be published!');
            return new RedirectResponse($this->get('router')->generate('admin_magenta_cbookmodel_book_book_show', ['id' => $object->getId()]));
        }

        $edition = $request->request->get('edition-text');
        $object->setBookEdition($edition);

        $clonedBook = $object->publish();
        $manager = $this->get('doctrine.orm.default_entity_manager');
        $manager->persist($object);
        $manager->persist($clonedBook);
        $manager->flush();

        $this->addFlash('success', 'Book Edition ' . $object->getBookEdition() . ' has been published');
        return new RedirectResponse($this->get('router')->generate('admin_magenta_cbookmodel_book_book_show', ['id' => $clonedBook->getId()]));
    }

    /**
     * @param Book $object
     *
     * @return RedirectResponse
     */
    protected function redirectTo($object)
    {
        $request = $this->getRequest();

        if (null !== $request->get('btn_create_and_edit')) {
            return new RedirectResponse($this->admin->generateUrl('show', ['id' => $object->getId()]));
        }

        return parent::redirectTo($object);
    }

    public function createAction()
    {
        return parent::createAction();
    }

    public function showAction($id = null)
    {
        $this->admin->setTemplate('show', '@MagentaCBookAdmin/Admin/Book/Book/CRUD/show.html.twig');
        return parent::showAction($id);
    }

    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Book/Book/CRUD/list.html.twig');

        return parent::listAction();
    }
}
