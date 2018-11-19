<?php
namespace Bean\Component\Messaging\Model;


use Bean\Component\CreativeWork\Model\CreativeWorkInterface;
use Bean\Component\Thing\Model\ThingInterface;

/**
 * A single message from a sender to one or more organizations or people.
 */
interface MessageInterface extends CreativeWorkInterface
{
    /**
     * @return ConversationInterface|null
     */
    public function getConversation(): ?ConversationInterface;

    /**
     * @param ConversationInterface $conversation
     */
    public function setConversation(ConversationInterface $conversation): void;

    /**
     * @return ThingInterface
     */
    public function getSender(): ThingInterface;

    /**
     * @param ThingInterface $sender
     */
    public function setSender(ThingInterface $sender): void;

    /**
     * @return \DateTime|null
     */
    public function getDateRead(): ?\DateTime;

    /**
     * @param \DateTime|null $dateRead
     */
    public function setDateRead(?\DateTime $dateRead): void;

    /**
     * @return \DateTime|null
     */
    public function getDateReceived(): ?\DateTime;

    /**
     * @param \DateTime|null $dateReceived
     */
    public function setDateReceived(?\DateTime $dateReceived): void;

    /**
     * @return \DateTime|null
     */
    public function getDateSent(): ?\DateTime;

    /**
     * @param \DateTime|null $dateSent
     */
    public function setDateSent(?\DateTime $dateSent): void;

    /**
     * @return array|\ArrayAccess|\Countable|\IteratorAggregate|null
     */
    public function getDeliveries();

    /**
     * @param array|\ArrayAccess|\Countable|\IteratorAggregate|null $deliveries
     */
    public function setDeliveries($deliveries): void;
}