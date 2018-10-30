<?php

namespace App\Controller;

use Bean\Component\Thing\Model\Thing;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use function GuzzleHttp\Psr7\copy_to_string;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\BookPage;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing\DPJob;
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
        $dp = $this->getDoctrine()->getRepository(DPJob::class)->find(3);
        $this->get('magenta_book.individual_service')->importMembers($dp);
        return new JsonResponse(['link' => 'https://picsum.photos/1600/900']);
    }
}
