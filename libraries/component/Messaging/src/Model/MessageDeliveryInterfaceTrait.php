<?php
declare(strict_types=1);

namespace Bean\Component\Messaging\Model;

use Bean\Component\Messaging\IoC\MessageDeliverableInterface;
use Bean\Component\Thing\Model\Thing;
use Bean\Component\Thing\Model\ThingInterfaceTrait;

trait MessageDeliveryInterfaceTrait
{
    use ThingInterfaceTrait;

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
