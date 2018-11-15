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
}
