<?php

namespace Magenta\Bundle\CBookAdminBundle\Admin\Book;

use Bean\Component\Book\Model\ChapterInterface;
use Magenta\Bundle\CBookAdminBundle\Admin\BaseCRUDAdminController;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;

class ChapterAdminController extends BaseCRUDAdminController
{
    /** @var ChapterAdmin $admin */
    protected $admin;

    public function moveAction($id = null, Request $request)
    {
        $id = $request->get($this->admin->getIdParameter());
        /** @var Chapter $object */
        $object = $this->admin->getObject($id);
        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the object with id: %s', $id));
        }
        $chapterRepo = $this->getDoctrine()->getRepository(Chapter::class);
        $manager = $this->get('doctrine.orm.default_entity_manager');

        /**
         * moved_node: 23
         * target_node: 24
         * position: before, after, inside
         * previous_parent: 24, null
         */

        $movedId = $request->request->getInt('moved_node', 0);
        $targetId = $request->request->getInt('target_node', 0);
        $positionType = $request->request->get('position', '');
        $previousParentId = $request->request->getInt('previous_parent', 0);
        if (!empty($previousParentId) && !empty($prevParent = $chapterRepo->find($previousParentId))) {
            $prevParent->removeSubChapter($object);
        }

        if (!empty($targetChapter = $chapterRepo->find($targetId))) {
            if ($positionType === 'inside') {
                $targetChapter->addSubChapter($object);
                $object->setPosition($targetChapter->getSubChapters()->count());
                $position = null;
            } elseif ($positionType === 'before') {
                $position = $targetChapter->getPosition();
                $object->setParentChapter($targetChapter->getParentChapter());
                $manager->persist($object);
                $manager->flush($object);
            } elseif ($positionType === 'after') {
                $position = $targetChapter->getPosition() + 1;
                $object->setParentChapter($targetChapter->getParentChapter());
                $manager->persist($object);
                $manager->flush($object);
            }

            if ($position !== null) {
                $parentChapter = $object->getParentChapter();
                $object->setPosition($position);

                $qb = $manager->createQueryBuilder('e');
                $expr = $qb->expr();
                $qb
                    ->update(Chapter::class, 'e')
                    ->set('e.position', 'e.position + 1')
                    ->andWhere('e.position >= :sort')
                    ->andWhere($expr->eq('e.book', $object->getBook()->getId()));
                if (!empty($parentChapter)) {
                    $qb->andWhere($expr->eq('e.parentChapter', $parentChapter->getId()));
                } else {
                    $qb->andWhere($expr->isNull('e.parentChapter'));
                }
                $qb
                    ->setParameter('sort', $position);

                $qb
                    ->getQuery()
                    ->execute();
                $this->get('magenta_book.book_service')->rearrangeChapters($object->getBook());
            }
        };

        $manager->persist($object);
        $manager->flush();

        return new JsonResponse(['OK']);
    }

    public function deleteChapterAction(Request $request, $id = null)
    {
        $id = $request->get($this->admin->getIdParameter());

        /** @var Chapter $object */
        $object = $this->admin->getObject($id);

        if (!$object) {
            throw $this->createNotFoundException(sprintf('unable to find the Chapter with id: %s', $id));
        }

        $this->admin->checkAccess('delete');

        if ($request->isMethod('post')) {
            $manager = $this->get('doctrine.orm.default_entity_manager');
            $chapterId = null;
            if (!empty($parentChapter = $object->getParentChapter())) {
                $chapterId = $parentChapter->getId();
            } else {
                $book = $object->getBook();
            }

            $manager->remove($object);
            $manager->flush();
            $this->get('magenta_book.book_service')->rearrangeChapters($object->getBook());
            if (empty($chapterId)) {
                return new RedirectResponse($this->get('router')->generate('admin_magenta_cbookmodel_book_book_show', ['id' => $book->getId()]));
            }

            return new RedirectResponse($this->admin->generateUrl('show', ['id' => $chapterId]));
        }
    }

    public function createChapterAction(Request $request)
    {
        $bookId = $request->request->getInt('book-id');
        $parentChapterId = $request->request->getInt('parent-chapter-id');
        $chapterName = $request->request->get('chapter-name');
        $isSubChapter = $request->request->getBoolean('is-subchapter', false);

        $registry = $this->getDoctrine();
        $chapterRepo = $registry->getRepository(Chapter::class);
        $bookRepo = $registry->getRepository(Book::class);

        $book = $bookRepo->find($bookId);
        $chapter = new Chapter();
        $chapter->setName($chapterName);
        $chapter->setEnabled(true);
        $book->addChapter($chapter);


        if (!empty($parentChapter = $chapterRepo->find($parentChapterId))) {
            if ($isSubChapter) {
                $parentChapter->addSubChapter($chapter);
            } else {
                /** @var ChapterInterface $grandParentChapter */
                if (!empty($grandParentChapter = $parentChapter->getParentChapter())) {
                    $grandParentChapter->addSubChapter($chapter);
                }
            }

        }


        $manager = $this->get('doctrine.orm.default_entity_manager');
        $manager->persist($chapter);
        $manager->flush();

        return new RedirectResponse($this->admin->generateUrl('show', ['id' => $chapter->getId()]));
    }

    public function renderWithExtraParams($view, array $parameters = [], Response $response = null)
    {
        if ($parameters['action'] === 'show') {
            /** @var Chapter $chapter */
            $chapter = $this->admin->getSubject();

            $orgSlug = "1";
            $accessCode = "1";
            $employeeCode = "1";
            $parameters['base_book_template'] = '@MagentaCBookAdmin/standard_layout.html.twig';
            $parameters['book'] = $this->admin->getSubject()->getBook();
            $parameters['currentChapter'] = $chapter;
            $parameters['mainContentItem'] = $chapter;
            $parameters['subContentItems'] = $chapter->getSubChapters();
            $parameters['orgSlug'] = $orgSlug;
            $parameters['accessCode'] = $accessCode;
            $parameters['employeeCode'] = $employeeCode;
        }

        return parent::renderWithExtraParams($view, $parameters, $response);
    }

    public function showAction($id = null)
    {
        $this->admin->setTemplate('show', '@MagentaCBookAdmin/Admin/Book/Chapter/CRUD/show.html.twig');

        return parent::showAction($id);
    }

    public function listAction()
    {
        $this->admin->setTemplate('list', '@MagentaCBookAdmin/Admin/Book/Chapter/CRUD/list.html.twig');

        return parent::listAction();
    }
}
