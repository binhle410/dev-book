<?php

namespace Magenta\Bundle\CBookModelBundle\EventListener\Notification;

use Doctrine\ORM\EntityManager;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing\DPJob;
use Magenta\Bundle\CBookModelBundle\Service\Organisation\IndividualMemberService;
use Magenta\Bundle\CBookModelBundle\Service\Spreadsheet\SpreadsheetService;
use Sonata\NotificationBundle\Consumer\ConsumerEvent;
use Sonata\NotificationBundle\Consumer\ConsumerInterface;
use Sonata\NotificationBundle\Model\MessageInterface;
use Sonata\CoreBundle\Exception\InvalidParameterException;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MemberImportConsumer implements ConsumerInterface
{
    protected $memberService;
    protected $manager;
    protected $registry;

    public function __construct(EntityManager $manager, RegistryInterface $registry, IndividualMemberService $ms)
    {
        $this->manager = $manager;
        $this->registry = $registry;
        $this->memberService = $ms;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ConsumerEvent $event)
    {
        $message = $event->getMessage();

        $dp = $this->registry->getRepository(DPJob::class)->find($message->getValue('job-id'));

        if (!empty($dp)) {
            $dp->setStatus(DPJob::STATUS_LOCKED);
            $this->manager->persist($dp);
            $this->manager->flush();
            $this->memberService->importMembers($dp);
        }
    }
}
