<?php
namespace Bean\Component\Messaging\Model;

use Bean\Component\Messaging\IoC\MessageDeliverableInterface;
use Bean\Component\Thing\Model\ThingInterface;

/**
 * NOT part of schema.org
 */
interface MessageDeliveryInterface extends ThingInterface
{
    /**
     * @return MessageInterface
     */
    public function getMessage(): MessageInterface;

    /**
     * @param MessageInterface $message
     */
    public function setMessage(MessageInterface $message): void;

    /**
     * @return \DateTime|null
     */
    public function getDateRead(): ?\DateTime;

    /**
     * @param \DateTime|null $dateRead
     */
    public function setDateRead(?\DateTime $dateRead): void;

    /**
     * @return MessageDeliverableInterface
     */
    public function getRecipient(): MessageDeliverableInterface;

    /**
     * @param MessageDeliverableInterface $recipient
     */
    public function setRecipient(MessageDeliverableInterface $recipient): void;
}