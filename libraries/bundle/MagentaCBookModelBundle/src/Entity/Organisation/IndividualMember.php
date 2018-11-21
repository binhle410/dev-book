<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Messaging\IoC\MessageContainerInterface;
use Bean\Component\Messaging\IoC\MessageDeliverableInterface;
use Bean\Component\Organization\Model\IndividualMember as MemberModel;

use Bean\Component\Organization\Model\OrganizationInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message;
use Magenta\Bundle\CBookModelBundle\Entity\Messaging\MessageDelivery;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;
use Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACRole;
use Magenta\Bundle\CBookModelBundle\Entity\System\ProgressiveWebApp\Subscription;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;

/**
 * @ORM\Entity(repositoryClass="Magenta\Bundle\CBookModelBundle\Repository\Organisation\IndividualMemberRepository")
 * @ORM\Table(name="organisation__individual_member")
 */
class IndividualMember extends MemberModel implements MessageDeliverableInterface, MessageContainerInterface
{
    
    private $messageDeliveryCache = [];
    
    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    public function __construct()
    {
        parent::__construct();
        $this->groupIndividuals = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->messageDeliveries = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->enabled = true;
    }
    
    public function readFromNotification(Message $message, Subscription $subscription)
    {
        /**
         * @var MessageDelivery $delivery
         */
        $delivery = $this->getMessageDelivery($message);
        if (empty($delivery)) {
            return null;
        }
        $delivery->setFirstReadFrom($subscription);
        $delivery->setDateRead(new \DateTime());
        return $delivery;
    }
    
    public function deliver(Message $message)
    {
        if (empty($message->getSender())) {
            $message->setSender($this);
        }
        if (empty($message->getOrganisation())) {
            $message->setOrganization($this->organization);
        }
        return $message->deliver();
    }
    
    /**
     * @param Organisation $org
     * @param Person $person
     * @param null $email
     * @return IndividualMember
     */
    public static function createInstance(Organisation $org, Person $person, $email = null)
    {
        $member = new IndividualMember();
        $member->setEnabled(true);
        $org->addIndividualMember($member);
        
        $person->addIndividualMember($member);
        $member->setEmail($email);
        return $member;
    }
    
    public function isMessageDelivered(Message $message)
    {
        if (empty($this->getMessageDelivery($message))) {
            return false;
        }
        return true;
    }
    
    public function getMessageDelivery(Message $message)
    {
        if (array_key_exists($message->getId(), $this->messageDeliveryCache)) {
            if ($this->messageDeliveryCache[$message->getId()]) {
                return $this->messageDeliveryCache[$message->getId()];
            }
        }
        $c = Criteria::create();
        $expr = Criteria::expr();
        
        $c->where($expr->eq('message', $message));
        $deliveries = $this->messageDeliveries->matching($c);
        if ($deliveries->count() > 0) {
            return $this->messageDeliveryCache[$message->getId()] = $deliveries->first();
            
        }
        return null;
    }
    
    public function getMessageDeliveryBySubscription(Message $message, Subscription $subscription)
    {
        if (array_key_exists($message->getId(), $this->messageDeliveryCache)) {
            if ($this->messageDeliveryCache[$message->getId()]) {
                return $this->messageDeliveryCache[$message->getId()];
            }
        }
        $c = Criteria::create();
        $expr = Criteria::expr();
        
        $c->where($expr->eq('message', $message));
        $deliveries = $this->messageDeliveries->matching($c);
        if ($deliveries->count() > 0) {
            return $this->messageDeliveryCache[$message->getId()] = $deliveries->first();
            
        }
        return null;
    }
    
    public function getBooksToRead()
    {
        $draftBooks = $this->organization->getDraftBooksHavingPreviousVersions();
        $books = [];
        /** @var Book $b */
        foreach ($draftBooks as $b) {
            if ($b->isAccessibleToIndividual($this)) {
                $books[] = $b;
            }
        }
        return $books;
    }
    
    public function initiatePin()
    {
        if (empty($this->pin)) {
            $this->pin = str_replace('O', '0', User::generate4DigitCode());
        }
        return $this;
    }
    
    public function initiateCode()
    {
        if (empty($this->code)) {
            $this->code = str_replace('O', '0', User::generate4DigitCode() . '-' . User::generateTimestampBasedCode());
        }
        return $this;
    }
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualGroup", inversedBy="members")
     * @ORM\JoinTable(name="organisation__individual_member__members_groups",
     *      joinColumns={@ORM\JoinColumn(name="id_individual", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_group", referencedColumnName="id")}
     *      )
     */
    protected $groups;
    
    public function addGroup(IndividualGroup $gc)
    {
        $this->groups->add($gc);
    }
    
    public function removeGroup(IndividualGroup $gc)
    {
        $this->groups->removeElement($gc);
    }
    
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\ProgressiveWebApp\Subscription", mappedBy="individualMember")
     */
    protected $subscriptions;
    
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Messaging\MessageDelivery", mappedBy="recipient")
     */
    protected $messageDeliveries;
    
    
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message", mappedBy="sender")
     */
    protected $messages;
    
    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\GroupIndividual", mappedBy="individualMember")
     */
    protected $groupIndividuals;
    
    public function addGroupIndividual(GroupIndividual $gm)
    {
        $this->groupIndividuals->add($gm);
        $gm->setMember($this);
    }
    
    public function removeGroupIndividual(GroupIndividual $gm)
    {
        $this->groupIndividuals->removeElement($gm);
        $gm->setMember(null);
    }
    
    /**
     * @var Organisation
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="individualMembers")
     * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $organization;
    
    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Person\Person", inversedBy="individualMembers")
     * @ORM\JoinColumn(name="id_person", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $person;
    
    /**
     * @var ACRole|null
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACRole", inversedBy="individualMembers")
     * @ORM\JoinColumn(name="id_role", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $role;
    
    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $synchronisedAt;
    
    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $wellnessId;
    
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $department;
    
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $designation;
    
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $wellnessPin;
    
    
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $wellnessEmployeeCode;
    
    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":true})
     */
    protected $contactable = false;
    
    /**
     * @var string|null
     * @ORM\Column(type="string",nullable=true)
     */
    protected $email;
    
    /**
     * @var string|null
     * @ORM\Column(type="string", length=20,nullable=true, unique=true)
     */
    protected $code;
    
    /**
     * @var string|null
     * @ORM\Column(type="string",nullable=true)
     */
    protected $pin;
    
    /**
     * @return bool
     */
    public function isContactable(): bool
    {
        return $this->contactable;
    }
    
    /**
     * @param bool $contactable
     */
    public function setContactable(bool $contactable): void
    {
        $this->contactable = $contactable;
    }
    
    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }
    
    /**
     * @param null|string $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }
    
    /**
     * @return null|string
     */
    public function getPin(): ?string
    {
        return $this->pin;
    }
    
    /**
     * @param null|string $pin
     */
    public function setPin(?string $pin): void
    {
        $this->pin = $pin;
    }
    
    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * @param null|string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
    
    /**
     * @return ACRole|null
     */
    public function getRole(): ?ACRole
    {
        return $this->role;
    }
    
    /**
     * @param ACRole|null $role
     */
    public function setRole(?ACRole $role): void
    {
        $this->role = $role;
    }
    
    /**
     * @return Collection
     */
    public function getGroupIndividuals(): Collection
    {
        return $this->groupIndividuals;
    }
    
    /**
     * @param Collection $groupIndividuals
     */
    public function setGroupIndividuals(Collection $groupIndividuals): void
    {
        $this->groupIndividuals = $groupIndividuals;
    }
    
    /**
     * @return Collection
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }
    
    /**
     * @param Collection $groups
     */
    public function setGroups(Collection $groups): void
    {
        $this->groups = $groups;
    }
    
    /**
     * @return Organisation|null
     */
    public function getOrganization(): ?OrganizationInterface
    {
        return $this->organization;
    }
    
    /**
     * @return \DateTime|null
     */
    public function getSynchronisedAt(): ?\DateTime
    {
        return $this->synchronisedAt;
    }
    
    /**
     * @param \DateTime|null $synchronisedAt
     */
    public function setSynchronisedAt(?\DateTime $synchronisedAt): void
    {
        $this->synchronisedAt = $synchronisedAt;
    }
    
    /**
     * @return int|null
     */
    public function getWellnessId(): ?int
    {
        return $this->wellnessId;
    }
    
    /**
     * @param int|null $wellnessId
     */
    public function setWellnessId(?int $wellnessId): void
    {
        $this->wellnessId = $wellnessId;
    }
    
    /**
     * @return null|string
     */
    public function getWellnessPin(): ?string
    {
        return $this->wellnessPin;
    }
    
    /**
     * @param null|string $wellnessPin
     */
    public function setWellnessPin(?string $wellnessPin): void
    {
        $this->wellnessPin = $wellnessPin;
    }
    
    /**
     * @return null|string
     */
    public function getWellnessEmployeeCode(): ?string
    {
        return $this->wellnessEmployeeCode;
    }
    
    /**
     * @param null|string $wellnessEmployeeCode
     */
    public function setWellnessEmployeeCode(?string $wellnessEmployeeCode): void
    {
        $this->wellnessEmployeeCode = $wellnessEmployeeCode;
    }
    
    /**
     * @return Collection
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }
    
    /**
     * @param Collection $subscriptions
     */
    public function setSubscriptions(Collection $subscriptions): void
    {
        $this->subscriptions = $subscriptions;
    }
    
    /**
     * @return Collection
     */
    public function getMessageDeliveries(): Collection
    {
        return $this->messageDeliveries;
    }
    
    /**
     * @param Collection $messageDeliveries
     */
    public function setMessageDeliveries(Collection $messageDeliveries): void
    {
        $this->messageDeliveries = $messageDeliveries;
    }
    
    /**
     * @return Collection
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }
    
    /**
     * @param Collection $messages
     */
    public function setMessages(Collection $messages): void
    {
        $this->messages = $messages;
    }
    
    /**
     * @return null|string
     */
    public function getDepartment(): ?string
    {
        return $this->department;
    }
    
    /**
     * @param null|string $department
     */
    public function setDepartment(?string $department): void
    {
        $this->department = $department;
    }
    
    /**
     * @return null|string
     */
    public function getDesignation(): ?string
    {
        return $this->designation;
    }
    
    /**
     * @param null|string $designation
     */
    public function setDesignation(?string $designation): void
    {
        $this->designation = $designation;
    }
}
