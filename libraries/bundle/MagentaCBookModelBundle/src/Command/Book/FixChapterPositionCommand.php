<?php

namespace Magenta\Bundle\CBookModelBundle\Command\Book;

use Doctrine\Common\Persistence\ObjectManager;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
use Magenta\Bundle\CBookModelBundle\Service\Book\BookService;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Magenta\Bundle\CBookModelBundle\Service\User\UserManipulator;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\Question;

/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Luis Cordova <cordoval@gmail.com>
 * @author Lenar Lõhmus <lenar@city.ee>
 */
class FixChapterPositionCommand extends Command
{
    protected static $defaultName = 'magenta:book:fix-chapter-position';

    private $manager;
    private $registry;
    protected $bookService;

    public function __construct(ObjectManager $manager, RegistryInterface $registry, BookService $bs)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->registry = $registry;
        $this->bookService = $bs;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('magenta:book:fix-chapter-position')
            ->setDefinition(array(
                new InputArgument('bookId', InputArgument::OPTIONAL, 'Book ID')
            ))
            ->setDescription('Fix Chapter Position')
            ->setHelp(<<<'EOT'
The <info>magenta:book:fix-chapter-position</info> command fixes chapter position

  <info>php %command.full_name% matthieu ROLE_CUSTOM</info>
  <info>php %command.full_name% --super matthieu</info>
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bookRepo = $this->registry->getRepository(Book::class);
        $bookId = $input->getArgument('bookId');
        if (!empty($bookId)) {
            $book = $bookRepo->find($bookId);
            $books = [$book];
        } else {
            $books = $bookRepo->findBy(['enabled' => true, 'status' => Book::STATUS_PUBLISHED]);
            $books = array_merge($books, $bookRepo->findBy(['enabled' => true, 'status' => Book::STATUS_DRAFT]));
        }

        /** @var Book $book */
        foreach ($books as $book) {
//            $this->rearrangeChapters($chapters, $output);
            $output->writeln('Working on ' . $book->getName() . ' (' . $book->getId() . ')');
            $this->bookService->rearrangeChapters($book);
            $output->writeln('DONE with ' . $book->getName() . ' (' . $book->getId() . ')');
        }

        $output->writeln('DONE');
//        $this->manager->flush();
    }

}