<?php

namespace Magenta\Bundle\CBookAdminBundle\Controller;

use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class BookReaderController extends Controller
{
    public function loginAction()
    {
        return $this->render('@MagentaCBookAdmin/Book/login.html.twig', []);
    }

    public function indexAction($accessCode, $employeeCode)
    {
        $b1 = new Book();
        $b1->setName('Symfony Encore');
        $b2 = new Book();
        $b2->setName('React JS');
        $books = [$b1, $b2];
        return $this->render('@MagentaCBookAdmin/Book/index.html.twig', [
            'base_book_template' => '@MagentaCBookAdmin/Book/base.html.twig',
            'books' => $books,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }

    public function readBookAction($accessCode, $employeeCode, $bookId)
    {
        $this->checkAccess($accessCode, $employeeCode);
        $bookRepo = $this->getDoctrine()->getRepository(Book::class);
        $book = $bookRepo->find($bookId);

        return $this->render('@MagentaCBookAdmin/Book/read-book.html.twig', [
            'base_book_template' => '@MagentaCBookAdmin/Book/base.html.twig',
            'book' => $book,
            'mainContentItem' => $book,
            'subContentItems' => $book->getRootChapters(),
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }

    public function readChapterAction($accessCode, $employeeCode, $chapterId)
    {
        $registry = $this->getDoctrine();
        $chapterRepo = $registry->getRepository(Chapter::class);
        /** @var Chapter $chapter */
        $chapter = $chapterRepo->find($chapterId);
        $memberRepo = $registry->getRepository(IndividualMember::class);
        $member = $memberRepo->findOneByPinCodeEmployeeCode($accessCode, $employeeCode);
        if (empty($member) || !$member->isEnabled()) {
            $this->handleUnauthorisation();
        }

        return $this->render('@MagentaCBookAdmin/Book/read-chapter.html.twig', [
            'base_book_template' => '@MagentaCBookAdmin/Book/base.html.twig',
            'book' => $book = $chapter->getBook(),
            'mainContentItem' => $chapter,
            'subContentItems' => $chapter->getSubChapters(),
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }

    public function contactAction($accessCode, $employeeCode)
    {
        $this->checkAccess($accessCode, $employeeCode);
        $member = $this->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);
        $members = $member->getOrganization()->getIndividualMembers();
        $sortedMembers = [];
        /** @var IndividualMember $m */
        foreach ($members as $m) {
            if (!$m->isContactable()) {
                continue;
            }
            if (!array_key_exists($alpha = substr($m->getName(), 0, 1), $sortedMembers)) {
                $sortedMembers[$alpha] = [];
            }
            $sortedMembers[$alpha][] = $m;
        }

        return $this->render('@MagentaCBookAdmin/Book/contact.html.twig', [
            'base_book_template' => '@MagentaCBookAdmin/Book/base.html.twig',
            'members' => $sortedMembers,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }

    private function checkAccess($accessCode, $employeeCode)
    {
        $member = $this->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);
        if (empty($member) || !$member->isEnabled()) {
            $this->handleUnauthorisation();
        }
    }

    private function handleUnauthorisation()
    {
        throw new UnauthorizedHttpException('Cannot access book reader. Invalid access code');
    }

    private function getMemberByPinCodeEmployeeCode($accessCode, $employeeCode)
    {
        if (empty($this->member)) {
            $registry = $this->getDoctrine();
            $memberRepo = $registry->getRepository(IndividualMember::class);
            $this->member = $memberRepo->findOneByPinCodeEmployeeCode($accessCode, $employeeCode);
        }
        return $this->member;
    }
}
