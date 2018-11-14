<?php
declare(strict_types=1);

namespace Bean\Component\Messaging\Model;

use Bean\Component\Messaging\IoC\MessageDeliverableInterface;
use Bean\Component\Thing\Model\Thing;
use Bean\Component\Thing\Model\ThingInterfaceTrait;

/**
 * NOT part of schema.org
 */
class MessageDelivery extends Thing implements MessageDeliveryInterface
{
    use ThingInterfaceTrait;

    function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * The date/time at which the message has been read by the recipient if a single recipient exists.
     * @var \DateTime|null
     */
    protected $dateRead;

    /**
     * @var MessageDeliverableInterface
     */
    protected $recipient;

    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface
    {
        return $this->message;
    }

    /**
     * @param MessageInterface $message
     */
    public function setMessage(MessageInterface $message): void
    {
        $this->message = $message;
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
     * @return MessageDeliverableInterface
     */
    public function getRecipient(): MessageDeliverableInterface
    {
        return $this->recipient;
    }

    /**
     * @param MessageDeliverableInterface $recipient
     */
    public function setRecipient(MessageDeliverableInterface $recipient): void
    {
        $this->recipient = $recipient;
    }
}
