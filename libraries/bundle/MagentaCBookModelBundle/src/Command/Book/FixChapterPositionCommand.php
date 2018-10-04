<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magenta\Bundle\CBookModelBundle\Command\Book;

use Doctrine\Common\Persistence\ObjectManager;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Chapter;
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

    public function __construct(ObjectManager $manager, RegistryInterface $registry)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->registry = $registry;
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
        }

        /** @var Book $book */
        foreach ($books as $book) {
            $chapters = $book->rearrangeRootChapters();
            $this->rearrangeChapters($chapters, $output);
        }

        $output->writeln('Flushing');
        $this->manager->flush();
    }

    private function rearrangeChapters($chapters, $output)
    {
        /** @var Chapter $chapter */
        foreach ($chapters as $chapter) {
            $output->writeln('Persisting ' . $chapter->getName());
            $this->manager->persist($chapter);
            $subChapters = $chapter->rearrangeSubChapters();
            $this->rearrangeChapters($subChapters, $output);
        }
    }
}