<?php

namespace Magenta\Bundle\CBookAdminBundle\Controller;

use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class BookReaderController extends Controller
{
    public function landingAction($orgSlug, Request $request)
    {
        return $this->render('@MagentaCBookAdmin/Book/landing.html.twig', ['orgSlug' => $orgSlug]);
    }

    public function loginAction($orgSlug, Request $request)
    {
        if ($request->isMethod('post')) {
            $dobStr = $request->request->get('dob');
            $idNumber = $request->request->get('id-number');
            $orgCode = $request->request->get('organisation-code');
            if (empty($dobStr) || empty($idNumber) || empty($orgCode)) {
//                throw new UnauthorizedHttpException('Fields are required');
                $this->addFlash('error', 'Missing required Fields');
            } else {
                $dob = \DateTime::createFromFormat('Y-m-d', $dobStr);
                if (empty($dob)) {
                    $this->addFlash('error', 'Date of Birth is invalid');
                } else {
                    $repo = $this->getDoctrine()->getRepository(IndividualMember::class);
                    /** @var IndividualMember $member */
                    $member = $repo->findOneByOrganisationCodeNric(trim($orgCode), trim($idNumber));
                    if (!empty($member) && $member->getPerson()->getBirthDate()->format('Y-m-d') === $dob->format('Y-m-d')) {
                        return new RedirectResponse($this->get('router')->generate('magenta_book_index',
                            [
                                'orgSlug' => $orgSlug,
                                'accessCode' => $member->getPin(),
                                'employeeCode' => $member->getCode()
                            ]));
                    } else {
                        $this->addFlash('error', 'Invalid Credentials');
                    }
                }
            }
        }
        return $this->render('@MagentaCBookAdmin/Book/login.html.twig', []);
    }

    public function indexAction($orgSlug, $accessCode, $employeeCode)
    {
        try {
            $this->checkAccess($accessCode, $employeeCode);
        } catch (UnauthorizedHttpException $e) {
            return new RedirectResponse($this->get('router')->generate('magenta_book_login',
                [
                    'orgSlug' => $orgSlug
                ]));
        }

        $member = $this->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);
        $books = $member->getBooksToRead();

        return $this->render('@MagentaCBookAdmin/Book/index.html.twig', [
            'member' => $member,
            'logo' => $member->getOrganization()->getLogo(),
            'base_book_template' => '@MagentaCBookAdmin/Book/base.html.twig',
            'books' => $books,
            'orgSlug' => $orgSlug,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }

    public function readBookAction($orgSlug, $accessCode, $employeeCode, $bookId)
    {
        $this->checkAccess($accessCode, $employeeCode);
        $bookRepo = $this->getDoctrine()->getRepository(Book::class);
        $book = $bookRepo->find($bookId);
        $member = $this->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);

        if (empty($book)) {
            $this->addFlash('error', 'The Book you requested for could not be found!');
            return new RedirectResponse($this->get('router')->generate('magenta_book_index',
                [
                    'member' => $member,
                    'orgSlug' => $orgSlug,
                    'employeeCode' => $employeeCode,
                    'accessCode' => $accessCode
                ]));
        }

        return $this->render('@MagentaCBookAdmin/Book/read-book-onepage.html.twig', [
            'member' => $member,
            'base_book_template' => '@MagentaCBookAdmin/Book/base.html.twig',
            'book' => $book,
            'mainContentItem' => $book,
            'subContentItems' => $book->getRootChapters(),
            'orgSlug' => $orgSlug,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }

    public function readChapterAction($orgSlug, $accessCode, $employeeCode, $chapterId)
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
            'member' => $member,
            'base_book_template' => '@MagentaCBookAdmin/Book/base.html.twig',
            'book' => $book = $chapter->getBook(),
            'mainContentItem' => $chapter,
            'subContentItems' => $chapter->getSubChapters(),
            'orgSlug' => $orgSlug,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }

    public function contactAction($orgSlug, $accessCode, $employeeCode)
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
            if (!array_key_exists($alpha = substr($m->getPerson()->getName(), 0, 1), $sortedMembers)) {
                $sortedMembers[$alpha] = [];
            }
            $sortedMembers[$alpha][] = $m;
        }
        ksort($sortedMembers);
        return $this->render('@MagentaCBookAdmin/Book/contact.html.twig', [
            'member' => $member,
            'base_book_template' => '@MagentaCBookAdmin/Book/base.html.twig',
            'members' => $sortedMembers,
            'orgSlug' => $orgSlug,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }

    private function checkAccess($accessCode, $employeeCode)
    {
        $member = $this->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);
        if (empty($member) || !$member->isEnabled() || !$member->getOrganization()->isEnabled()) {
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
