<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Messaging;

use Bean\Component\CreativeWork\Model\CreativeWorkTrait;
use Bean\Component\Messaging\IoC\MessageDeliverableInterface;
use Bean\Component\Messaging\Model\MessageDeliveryInterface;
use Bean\Component\Messaging\Model\MessageDeliveryInterfaceTrait;
use Bean\Component\Messaging\Model\MessageInterface;
use Bean\Component\Organization\Model\IndividualMember as MemberModel;

use Bean\Component\Thing\Model\Thing;
use Bean\Component\Thing\Model\ThingInterfaceTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\System\ProgressiveWebApp\Subscription;

/**
 * @ORM\Entity()
 * @ORM\Table(name="messaging__delivery")
 */
class MessageDelivery implements MessageDeliveryInterface
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="bigint",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    use MessageDeliveryInterfaceTrait;
    
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
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateRead;
    
    /**
     * The date/time at which the message has been sent to the recipient
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $createdAt;
    
    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;
    
    /**
     * @var boolean|null
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
}
