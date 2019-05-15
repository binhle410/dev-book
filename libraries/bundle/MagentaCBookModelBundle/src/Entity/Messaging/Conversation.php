<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Messaging;

use Bean\Component\Organization\IoC\OrganizationAwareInterface;
use Bean\Component\Organization\Model\OrganizationInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;

/**
 * @ORM\Entity()
 * @ORM\Table(name="messaging__conversation")
 */
class Conversation extends \Bean\Component\Messaging\Model\Conversation implements OrganizationAwareInterface
{
    /**
     * @var Organisation|null
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation")
     * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $organisation;
    
    /**
     * @ORM\OneToMany(
     *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message",
     *     mappedBy="conversation", cascade={"persist"}
     * )
     *
     * @var \Doctrine\Common\Collections\Collection $messages
     */
    protected $messages;
    
    
    public function getOrganization(): ?OrganizationInterface
    {
        return $this->getOrganisation();
    }
    
    public function setOrganization(?OrganizationInterface $organization)
    {
        $this->setOrganisation($organization);
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