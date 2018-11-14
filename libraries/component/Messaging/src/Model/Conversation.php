<?php
declare(strict_types=1);

namespace Bean\Component\Messaging\Model;

use Bean\Component\CreativeWork\Model\CreativeWork;

/**
 * One or more messages between organizations or people on a particular topic. Individual messages can be linked to the conversation with isPartOf or hasPart properties.
 */
class Conversation extends CreativeWork implements ConversationInterface
{
    /**
     * NOT part of schema.org
     * Collection of MessageDeliverableInterface
     * @var \Countable|\IteratorAggregate|\ArrayAccess|array|null
     */
    protected $participants;

    /**
     * NOT part of schema.org
     * @var \Countable|\IteratorAggregate|\ArrayAccess|array|null
     */
    protected $messages;

    public function addMessage(MessageInterface $message)
    {
        $this->addElementToArrayProperty($message, 'messages');
        $message->setConversation($this);
    }

    public function removeMessage(MessageInterface $message)
    {
        $this->removeElementFromArrayProperty($message, 'messages');
        $message->setConversation(null);
    }

    /**
     * @return array|\ArrayAccess|\Countable|\IteratorAggregate|null
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param array|\ArrayAccess|\Countable|\IteratorAggregate|null $participants
     */
    public function setParticipants($participants): void
    {
        $this->participants = $participants;
    }

    /**
     * @return array|\ArrayAccess|\Countable|\IteratorAggregate|null
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array|\ArrayAccess|\Countable|\IteratorAggregate|null $messages
     */
    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }
}
