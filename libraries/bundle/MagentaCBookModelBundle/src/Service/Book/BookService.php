<?php

namespace Magenta\Bundle\CBookModelBundle\Service\Book;

use Doctrine\Common\Persistence\ObjectManager;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
use Magenta\Bundle\CBookModelBundle\Service\BaseService;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BookService extends BaseService
{
    protected $manager;
    protected $registry;

    function __construct(ContainerInterface $container, ObjectManager $manager, RegistryInterface $registry)
    {
        parent::__construct($container);
        $this->manager = $manager;
        $this->registry = $registry;
    }


    public function rearrangeChapters(Book $book, $chapters = [], $depth = 0)
    {
        if ($depth === 0) {
            $chapters = $book->rearrangeRootChapters();
            $this->rearrangeChapters($book, $chapters, ++$depth);
            $this->manager->flush();
        } else {
            /** @var Chapter $chapter */
            foreach ($chapters as $chapter) {
//            $output->writeln('Persisting ' . $chapter->getName());
                $this->manager->persist($chapter);
                $subChapters = $chapter->rearrangeSubChapters();
                $this->rearrangeChapters($book, $subChapters, ++$depth);
            }
        }
    }
}