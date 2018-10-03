<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Organization\Model\IndividualMember as MemberModel;

use Bean\Component\Organization\Model\OrganizationInterface;
use Bean\Component\Person\Model\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Book\Book;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACRole;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;

/**
 * @ORM\Entity(repositoryClass="Magenta\Bundle\CBookModelBundle\Repository\Organisation\IndividualMemberRepository")
 * @ORM\Table(name="organisation__individual_member")
 */
class IndividualMember extends MemberModel
{

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
        $this->enabled = true;
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
        if (empty($this->pinCode)) {
            $this->pin = str_replace('O', '0', User::generate4DigitCode());
        }
        return $this;
    }

    public function initiateCode()
    {
        if (empty($this->employeeCode)) {
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
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":true})
     */
    protected $contactable = true;

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
}
