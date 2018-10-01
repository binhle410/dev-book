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
        $registry = $this->getDoctrine();
        $bookRepo = $registry->getRepository(Book::class);
        $book = $bookRepo->find($bookId);
        $memberRepo = $registry->getRepository(IndividualMember::class);
        $member = $memberRepo->findOneByPinCodeEmployeeCode($accessCode, $employeeCode);
        if (empty($member) || !$member->isEnabled()) {
            throw new UnauthorizedHttpException('Cannot access book reader. Invalid access code');
        }

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
    }

    public function contactAction($accessCode, $employeeCode)
    {
        return $this->render('@MagentaCBookAdmin/Book/contact.html.twig', [
            'base_book_template' => '@MagentaCBookAdmin/Book/base.html.twig',
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }
}
