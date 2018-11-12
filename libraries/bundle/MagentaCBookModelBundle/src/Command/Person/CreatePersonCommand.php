<?php
/*
 * This file is part of the FOSpersonBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Magenta\Bundle\CBookModelBundle\Command\Person;

use Doctrine\ORM\EntityManager;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Service\person\personManipulator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Peter Bean (peter@magenta-wellness.com)
 * @author binh@sunrise.vn <binh@sunrise.vn>
 */
class CreatePersonCommand extends Command
{
    protected static $defaultName = 'magenta:person:create';

    private $manager;

    public function __construct(EntityManager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Create a person.')
            ->setDefinition(array(
                new InputArgument('id-number', InputArgument::REQUIRED, 'NRIC/ID No'),
                new InputArgument('dob', InputArgument::REQUIRED, 'DOB'),
                new InputArgument('given-name', InputArgument::REQUIRED, 'Given Name/First Name'),
                new InputArgument('family-name', InputArgument::REQUIRED, 'Family Name/Last Name'),
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
            ))
            ->setHelp(<<<'EOT'
The <info>magenta:person:create</info> command creates a person:

  <info>php %command.full_name% 024290123 01-12-1987 Peter Bean peter@magenta-wellness.com</info>

EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dobStr = $input->getArgument('dob');
        $dob = \DateTime::createFromFormat('d-m-Y', $dobStr);
        $idNumber = $input->getArgument('id-number');
        $familyName = $input->getArgument('given-name');
        $givenName = $input->getArgument('family-name');
        $email = $input->getArgument('email');

        $p = Person::createInstance($idNumber, $dob, $givenName, $familyName, $email);
        $this->manager->persist($p);
        $this->manager->flush();

        $output->writeln(sprintf('Created person <comment>%s</comment>', $givenName . ' ' . $familyName));
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('id-number')) {
            $question = new Question('Please enter the NRIC/Passport/Work Permit/ID Number:');
            $question->setValidator(function ($personname) {
                if (empty($personname)) {
                    throw new \Exception('ID No. can not be empty');
                }

                return $personname;
            });
            $questions['id-number'] = $question;
        }


//        if (!$input->getArgument('password')) {
//            $question = new Question('Please choose a password:');
//            $question->setValidator(function ($password) {
//                if (empty($password)) {
//                    throw new \Exception('Password can not be empty');
//                }
//
//                return $password;
//            });
//            $question->setHidden(true);
//            $questions['password'] = $question;
//        }

//        foreach ($questions as $name => $question) {
//            $answer = $this->getHelper('question')->ask($input, $output, $question);
//            $input->setArgument($name, $answer);
//        }
    }
}