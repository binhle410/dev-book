<?php

namespace Bean\Component\Messaging\Model;

use Bean\Component\CreativeWork\Model\CreativeWorkInterface;
use Bean\Component\Messaging\IoC\MessageContainerInterface;

/**
 * One or more messages between organizations or people on a particular topic. Individual messages can be linked to the conversation with isPartOf or hasPart properties.
 */
interface ConversationInterface extends CreativeWorkInterface, MessageContainerInterface
{
    public function addMessage(MessageInterface $message);

    public function removeMessage(MessageInterface $message);

    /**
     * @return array|\ArrayAccess|\Countable|\IteratorAggregate|null
     */
    public function getParticipants();

    /**
     * @param array|\ArrayAccess|\Countable|\IteratorAggregate|null $participants
     */
    public function setParticipants($participants): void;

    /**
     * @return array|\ArrayAccess|\Countable|\IteratorAggregate|null
     */
    public function getMessages();

    /**
     * @param array|\ArrayAccess|\Countable|\IteratorAggregate|null $messages
     */
    public function setMessages($messages): void;
}