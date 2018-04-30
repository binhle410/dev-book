<?php

namespace App\Controller;

use Bean\Bundle\BookBundle\Doctrine\Orm\Chapter;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BeanPlaygroundController extends Controller
{
    /**
     * @Route("/bean/playground", name="bean_playground")
     */
    public function index()
    {
    	$registry = $this->get('doctrine');
    	$chapRepo = $registry->getRepository(Chapter::class);
    	$chap = $chapRepo->findAll()[0];
    	
    	
        return $this->render('bean_playground/index.html.twig', [
            'controller_name' => 'BeanPlaygroundController',
	        'chap' => $chap
        ]);
    }
}
