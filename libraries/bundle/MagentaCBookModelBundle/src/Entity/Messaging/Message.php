<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Messaging;

use Bean\Component\Organization\IoC\OrganizationAwareInterface;
use Bean\Component\Organization\Model\IndividualMember as MemberModel;

use Bean\Component\Organization\Model\OrganizationInterface;
use Bean\Component\Thing\Model\Thing;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\AccessControl\ACRole;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;

/**
 * @ORM\Entity()
 * @ORM\Table(name="messaging__message")
 */
class Message extends \Bean\Component\Messaging\Model\Message implements OrganizationAwareInterface
{
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function markStatusAsDeliveryInProgress()
    {
        if ($this->status === self::STATUS_NEW) {
            $this->status = self::STATUS_DELIVERY_IN_PROGRESS;
        }
    }
    
    /**
     * @var Organisation|null
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation")
     * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $organisation;
    
    /**
     * @var Conversation|null
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Messaging\Conversation", inversedBy="messages")
     * @ORM\JoinColumn(name="id_conversation", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $conversation;
    
    public function getOrganization(): ?OrganizationInterface
    {
        return $this->organisation;
    }
    
    public function setOrganization(?OrganizationInterface $organization)
    {
        $this->organisation = $organization;
    }
    
    /**
     * @return Organisation|null
     */
    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }
    
    /**
     * @param Organisation|null $organisation
     */
    public function setOrganisation(?Organisation $organisation): void
    {
        $this->organisation = $organisation;
    }
}
