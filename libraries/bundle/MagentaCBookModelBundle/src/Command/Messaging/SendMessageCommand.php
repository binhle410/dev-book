<?php

namespace Magenta\Bundle\CBookModelBundle\Command\Messaging;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing\DPJob;
use Magenta\Bundle\CBookModelBundle\Service\Classification\CategoryManager;
use Magenta\Bundle\CBookModelBundle\Service\Organisation\IndividualMemberService;
use Magenta\Bundle\CBookModelBundle\Service\User\UserManipulator;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Luis Cordova <cordoval@gmail.com>
 */
class SendMessageCommand extends Command
{
    protected static $defaultName = 'magenta:messaging:send-messages';
    
    private $manager;
    private $registry;
    private $memberService;
    
    public function __construct(EntityManager $manager, RegistryInterface $registry, IndividualMemberService $memberService)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->registry = $registry;
        $this->memberService = $memberService;
    }
    
    
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::$defaultName)
            ->setDescription('Send Messages from a DPJob.')
            ->setDefinition(array(
                new InputArgument('dpjob', InputArgument::REQUIRED, 'DPJob Id')
            ))
            ->setHelp(<<<'EOT'
The <info>magenta:messaging:send-messages</info>
EOT
            );
    }
    
    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dpjobId = $input->getArgument('dpjob');
        
        $output->writeln('Sending messages from ' . $dpjobId);
        $dp = $this->registry->getRepository(DPJob::class)->find($dpjobId);
    
        $this->memberService->notifyOneOrganisationIndividualMembers($dp);
        
        $output->writeln('Flusing');
        $this->manager->flush();
        $output->writeln('DONE');
    }
    
}