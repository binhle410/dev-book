<?php

namespace Magenta\Bundle\CBookAdminBundle\Controller;

use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\CategoryItem;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\System\ProgressiveWebApp\Subscription;
use Magenta\Bundle\CBookModelBundle\Service\Organisation\IndividualMemberService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
    
    public function indexAction($orgSlug, $accessCode, $employeeCode, Request $request)
    {
        try {
            $this->checkAccess($accessCode, $employeeCode, $orgSlug);
        } catch (UnauthorizedHttpException $e) {
            return new RedirectResponse($this->get('router')->generate('magenta_book_login',
                [
                    'orgSlug' => $orgSlug
                ]));
        }
        
        $registry = $this->getDoctrine();
        
        $member = $this->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);
//        $books = $member->getBooksToRead();
        
        /** @var Organisation $org */
        $org = $member->getOrganization();
        
        $rootCategory = $org->getRootCategoriesByContext($registry->getRepository(Context::class)->find('default'))->first();
        
        $registry = $this->getDoctrine();
        $parentId = $request->query->get('parent');
        $selectedCategory = null;
        if (!empty($parentId)) {
            $catRepo = $registry->getRepository(Category::class);
            $selectedCategory = $catRepo->find($parentId);
        }
        
        if (empty($selectedCategory)) {
            $selectedCategory = $rootCategory;
        }
        
        return $this->render('@MagentaCBookAdmin/App/index.html.twig', [
            'rootCategory' => $rootCategory,
            'selectedCategory' => $selectedCategory,
            'member' => $member,
            'logo' => $member->getOrganization()->getLogo(),
            'base_book_template' => '@MagentaCBookAdmin/App/base.html.twig',
//            'books' => $books,
            'orgSlug' => $orgSlug,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }
    
    public function readBookAction($orgSlug, $accessCode, $employeeCode, $bookId)
    {
        $this->checkAccess($accessCode, $employeeCode, $orgSlug);
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
        
        return $this->render('@MagentaCBookAdmin/App/Book/read-book-onepage.html.twig', [
            'logo' => $member->getOrganization()->getLogo(),
            'member' => $member,
            'base_book_template' => '@MagentaCBookAdmin/App/base.html.twig',
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
        /** @var Book $book */
        $book = $chapter->getBook();
        return $this->render('@MagentaCBookAdmin/Book/read-chapter.html.twig', [
            'member' => $member,
            'base_book_template' => '@MagentaCBookAdmin/App/base.html.twig',
            'book' => $book,
            'mainContentItem' => $chapter,
            'subContentItems' => $chapter->getSubChapters(),
            'orgSlug' => $orgSlug,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }
    
    public function contactAction($orgSlug, $accessCode, $employeeCode)
    {
        $this->checkAccess($accessCode, $employeeCode, $orgSlug);
        $member = $this->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);
        $org = $member->getOrganization();
        $members = $org->getIndividualMembers();
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
        
        return $this->render('@MagentaCBookAdmin/App/contact.html.twig', [
            'member' => $member,
            'base_book_template' => '@MagentaCBookAdmin/App/base.html.twig',
            'logo' => $org->getLogo(),
            'members' => $sortedMembers,
            'orgSlug' => $orgSlug,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }
    
    public function readNotifAction($orgSlug, $accessCode, $employeeCode, $messageId, $subscriptionId, Request $request)
    {
        $this->get('magenta_book.individual_service')->checkAccess($accessCode, $employeeCode, $orgSlug);
        $member = $this->get('magenta_book.individual_service')->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);
        $registry = $this->getDoctrine();
        $message = $registry->getRepository(Message::class)->find($messageId);
        if (empty($message)) {
            throw new NotFoundHttpException();
        }
        $subscription = $registry->getRepository(Subscription::class)->find($subscriptionId);
        $delivery = $member->readFromNotification($message, $subscription);
        if (empty($delivery)) {
            throw new NotFoundHttpException('delivery not found');
        }
        $manager = $this->get('doctrine.orm.default_entity_manager');
        $manager->persist($delivery);
        $manager->flush();
        
        $org = $member->getOrganization();
        return $this->render('@MagentaCBookAdmin/App/Messaging/read-message.html.twig', [
            'message' => $message,
            'member' => $member,
            'base_book_template' => '@MagentaCBookAdmin/App/base.html.twig',
            'logo' => $org->getLogo(),
            'orgSlug' => $orgSlug,
            'accessCode' => $accessCode,
            'employeeCode' => $employeeCode
        ]);
    }
    
    private function checkAccess($accessCode, $employeeCode, $orgSlug = null)
    {
        $this->get('magenta_book.individual_service')->checkAccess($accessCode, $employeeCode, $orgSlug);
    }
    
    private function getMemberByPinCodeEmployeeCode($accessCode, $employeeCode)
    {
        if (empty($this->member)) {
            $this->member = $this->get('magenta_book.individual_service')->getMemberByPinCodeEmployeeCode($accessCode, $employeeCode);
        }
        return $this->member;
    }
}
