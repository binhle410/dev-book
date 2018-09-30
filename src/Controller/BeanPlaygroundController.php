<?php

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use function GuzzleHttp\Psr7\copy_to_string;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\BookPage;
use Sonata\MediaBundle\Form\Type\ApiMediaType;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BeanPlaygroundController extends Controller {
	
	public function __invoke(Book $data) {
		/** @var ArrayCollection $chapters */
		$chapters = $data->getChapters();
		
		$criteria = Criteria::create();
		$expr     = Criteria::expr();
		
		$criteria->where(
			$expr->eq('partOf', $data)
		);
		
		return $chapters->matching($criteria);
	}
	
	/**
	 * @Route("/bean/playground", name="bean_playground")
	 */
	public function index(Request $request) {
//	    $abc = $request->get('field_name');
//		$registry = $this->get('doctrine');
//		$chapRepo = $registry->getRepository(Chapter::class);
//		$chap     = $chapRepo->findAll()[0];
//		$chap     = $chapRepo->find(6);
//		$qb       = $this->container->get('doctrine.orm.default_entity_manager')->createQueryBuilder();
//
////		$qb->select('c')->from(Chapter::class, 'c')
////		   ->join('c.partOf', 'partOf');;
////		$chapter = $qb->setFirstResult(0)->getQuery()->getResult();


//	    $manager = $this->get('doctrine.orm.default_entity_manager');
//	    $page = new BookPage();
//	    $page->setText('here comes the text');
//	    $manager->persist($page);$manager->flush();
		$builder = $this->createFormBuilder();
		$builder->add('media', MediaType::class, array(
			'provider' => 'sonata.media.provider.file',
			'context'  => 'default'
		));

		$bookRepo = $this->getDoctrine()->getRepository(Book::class);
		$book = $bookRepo->find(7);

		$clonedBook = clone $book;

		$form = $builder->getForm();
		$data = null;
		
		if($request->getMethod() == 'POST') {
			$form->handleRequest($request);
			$data = $form->getData();
			
		}
		
		return $this->render('bean_playground/index.html.twig', [
			'form'            => $form->createView(),
			'data'            => $data,
			'controller_name' => 'BeanPlaygroundController',
			'chap'            => 'abc'
		]);
		
		return new JsonResponse([ 'link' => 'https://picsum.photos/1600/900' ]);
	}
}
