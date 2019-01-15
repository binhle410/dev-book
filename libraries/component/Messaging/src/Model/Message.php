<?php

declare(strict_types=1);

namespace Bean\Component\Messaging\Model;

use Bean\Component\CreativeWork\Model\CreativeWork;
use Bean\Component\Messaging\IoC\MessageContainerInterface;
use Bean\Component\Thing\Model\ThingInterface;

/**
 * A single message from a sender to one or more organizations or people.
 */
class Message extends CreativeWork implements MessageInterface
{
    const STATUS_DRAFT = 'MESSAGE_DRAFT';
    const STATUS_NEW = 'MESSAGE_NEW';
    const STATUS_DELIVERY_IN_PROGRESS = 'DELIVERY_IN_PROGRESS';
    const STATUS_DELIVERY_SUCCESSFUL = 'DELIVERY_SUCCESSFUL';
    const STATUS_RECEIVED = 'MESSAGE_RECEIVED';
    const STATUS_READ = 'MESSAGE_READ';

    public function __construct()
    {
        parent::__construct();
        $this->status = self::STATUS_DRAFT;
    }

    public function deliver()
    {
        if (self::STATUS_DRAFT === $this->status) {
            $this->status = self::STATUS_NEW;
        }

        return $this->status;
    }

    /**
     * NOT part of schema.org.
     *
     * @var \Countable|\IteratorAggregate|\ArrayAccess|array|null
     */
    protected $deliveries;

    public function addDelivery(MessageDelivery $delivery)
    {
        $this->addElementToArrayProperty($delivery, 'deliveries');
        $delivery->setMessage($this);
    }

    public function removeDelivery(MessageDelivery $delivery)
    {
        $this->removeElementFromArrayProperty($delivery, 'deliveries');
        $delivery->setMessage(null);
    }

    /**
     * NOT part of schema.org.
     *
     * @var ConversationInterface|null
     */
    protected $conversation;

    /**
     * @var MessageContainerInterface
     */
    protected $sender;

    /**
     * The date/time at which the message has been read by the recipient if a single recipient exists.
     *
     * @var \DateTime|null
     */
    protected $dateRead;

    /**
     * The date/time the message was received if a single recipient exists.
     *
     * @var \DateTime|null
     */
    protected $dateReceived;

    /**
     * The date/time at which the message was sent.
     *
     * @var \DateTime|null
     */
    protected $dateSent;

    /**
     * @return ConversationInterface|null
     */
    public function getConversation(): ?ConversationInterface
    {
        return $this->conversation;
    }

    /**
     * @param ConversationInterface $conversation
     */
    public function setConversation(ConversationInterface $conversation): void
    {
        $this->conversation = $conversation;
    }

    /**
     * @return ThingInterface|null
     */
    public function getSender(): ?ThingInterface
    {
        return $this->sender;
    }

    /**
     * @param ThingInterface $sender
     */
    public function setSender(ThingInterface $sender): void
    {
        $this->sender = $sender;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateRead(): ?\DateTime
    {
        return $this->dateRead;
    }

    /**
     * @param \DateTime|null $dateRead
     */
    public function setDateRead(?\DateTime $dateRead): void
    {
        $this->dateRead = $dateRead;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateReceived(): ?\DateTime
    {
        return $this->dateReceived;
    }

    /**
     * @param \DateTime|null $dateReceived
     */
    public function setDateReceived(?\DateTime $dateReceived): void
    {
        $this->dateReceived = $dateReceived;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateSent(): ?\DateTime
    {
        return $this->dateSent;
    }

    /**
     * @param \DateTime|null $dateSent
     */
    public function setDateSent(?\DateTime $dateSent): void
    {
        $this->dateSent = $dateSent;
    }

    /**
     * @return array|\ArrayAccess|\Countable|\IteratorAggregate|null
     */
    public function getDeliveries()
    {
        return $this->deliveries;
    }

    /**
     * @param array|\ArrayAccess|\Countable|\IteratorAggregate|null $deliveries
     */
    public function setDeliveries($deliveries): void
    {
        $this->deliveries = $deliveries;
    }
}
