<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use function GuzzleHttp\Psr7\copy_to_string;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\BookPage;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Sonata\MediaBundle\Form\Type\ApiMediaType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BeanPlaygroundController extends Controller
{

    public function __invoke(Book $data)
    {
        /** @var ArrayCollection $chapters */
        $chapters = $data->getChapters();

        $criteria = Criteria::create();
        $expr = Criteria::expr();

        $criteria->where(
            $expr->eq('partOf', $data)
        );

        return $chapters->matching($criteria);
    }

    /**
     * @Route("/bean/playground", name="bean_playground")
     */
    public function index(Request $request)
    {
        $abc = $request->get('field_name');
        $registry = $this->get('doctrine');
//		$chapRepo = $registry->getRepository(Chapter::class);
//		$chap     = $chapRepo->findAll()[0];
//		$chap     = $chapRepo->find(6);
//		$qb       = $this->container->get('doctrine.orm.default_entity_manager')->createQueryBuilder();

////		$qb->select('c')->from(Chapter::class, 'c')
////		   ->join('c.partOf', 'partOf');;
////		$chapter = $qb->setFirstResult(0)->getQuery()->getResult();

        /**
         * select substr(first_name,1,1) as alpha, count(employee_id)
         * from employees
         * group by substr(first_name,1,1)
         */
//        $qb = $this->container->get('doctrine.orm.default_entity_manager')->createQueryBuilder();
//        $expr = $qb->expr();
//        $expr->length("p.website");
//        $qb->select('m as member', $expr->substring("p.name", 1, '1') . ' as alpha');
//        $qb->from(IndividualMember::class, 'm')
//            ->join('m.person', 'p')
//            ->groupBy('alpha');
//        $results = $qb->getQuery()->getResult();

//	    $manager = $this->get('doctrine.orm.default_entity_manager');
//	    $page = new BookPage();
//	    $page->setText('here comes the text');
//	    $manager->persist($page);$manager->flush();
        $builder = $this->createFormBuilder();
        $builder->add('media', MediaType::class, array(
            'provider' => 'sonata.media.provider.file',
            'context' => 'default'
        ));

//        $bookRepo = $this->getDoctrine()->getRepository(Book::class);
//        $book = $bookRepo->find(7);
//
//        $clonedBook = clone $book;

        $form = $builder->getForm();
        $data = null;

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            $data = $form->getData();
        }

        $quotient = (int)(5 / 3);
        $remainder = 5 % 3;

        $this->container->get('sonata.classification.manager.category')->initiateRootCategories('organisation_logo');

        return $this->render('bean_playground/index.html.twig', [
            'form' => $form->createView(),
            'data' => $data,
            'controller_name' => 'BeanPlaygroundController',
            'chap' => 'abc ' . $quotient
        ]);

        return new JsonResponse(['link' => 'https://picsum.photos/1600/900']);
    }
}
