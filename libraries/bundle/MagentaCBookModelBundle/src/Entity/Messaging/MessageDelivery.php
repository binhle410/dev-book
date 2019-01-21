<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Messaging;

use Bean\Component\Messaging\IoC\MessageDeliverableInterface;
use Bean\Component\Messaging\Model\MessageDeliveryInterface;
use Bean\Component\Messaging\Model\MessageDeliveryInterfaceTrait;
use Bean\Component\Messaging\Model\MessageInterface;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\System\ProgressiveWebApp\Subscription;

/**
 * @ORM\Entity()
 * @ORM\Table(name="messaging__delivery")
 */
class MessageDelivery implements MessageDeliveryInterface
{
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(type="bigint",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    use MessageDeliveryInterfaceTrait;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        if (empty($this->createdAt)) {
            return $this->message->getCreatedAt();
        }

        return $this->createdAt;
    }

    public static function createInstance(Message $message, MessageDeliverableInterface $recipient)
    {
        $d = new MessageDelivery();
        $d->message = $message;
        $d->recipient = $recipient;
        $d->name = $message->getName();
        $d->description = $message->getText();
        $d->enabled = true;

        return $d;
    }

    /**
     * @var MessageInterface
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message", inversedBy="deliveries")
     * @ORM\JoinColumn(name="id_message", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $message;

    /**
     * @var MessageDeliverableInterface
     * @ORM\ManyToOne(targetEntity="Bean\Component\Thing\Model\Thing")
     * @ORM\JoinColumn(name="id_recipient", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $recipient;

    /**
     * @var Subscription|null
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\ProgressiveWebApp\Subscription", inversedBy="deliveries")
     * @ORM\JoinColumn(name="id_first_read_from", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $firstReadFrom;

    /**
     * The date/time at which the message has been read by the recipient if a single recipient exists.
     *
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateRead;

    /**
     * The date/time at which the message has been sent to the recipient.
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @var bool|null
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     */
    protected $locked = false;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Subscription|null
     */
    public function getFirstReadFrom(): ?Subscription
    {
        return $this->firstReadFrom;
    }

    /**
     * @param Subscription|null $firstReadFrom
     */
    public function setFirstReadFrom(?Subscription $firstReadFrom): void
    {
        $this->firstReadFrom = $firstReadFrom;
    }

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
     * @return bool|null
     */
    public function getLocked(): ?bool
    {
        return $this->locked;
    }

    /**
     * @param bool|null $locked
     */
    public function setLocked(?bool $locked): void
    {
        $this->locked = $locked;
    }
}
