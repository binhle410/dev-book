<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Person;

use Bean\Component\Organization\IoC\IndividualMemberContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Tag;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="person__person")
 */
class Person extends \Bean\Bundle\PersonBundle\Doctrine\Orm\Person implements IndividualMemberContainerInterface
{

    public function __construct()
    {
        parent::__construct();
        $this->individualMembers = new ArrayCollection();
    }

    public function initiateUser($emailRequired = true)
    {
        if (empty($this->user)) {
            $this->user = new User();
        }
        $this->user->setEnabled(true);

        $this->user->addRole(User::ROLE_POWER_USER);
        if ($emailRequired) {
            if (empty($this->email)) {
                if (empty($this->user->getEmail())) {
//					throw new \InvalidArgumentException('person email is null');
                    $today = new \DateTime();
                    if (empty($this->name)) {
                        $this->name = 'random-' . $today->getTimestamp();
                    }
                    $this->email = str_replace(' ', '-', $this->name) . '_' . $today->format('dmY') . '@no-email.com';
                } else {
                    $this->email = $this->user->getEmail();
                }
            }
        }
        $username = '';
        if (!empty($this->givenName)) {
            $username .= Tag::slugify(trim($this->givenName));
            $username .= '-';
        }
        if (!empty($this->familyName)) {
            $username .= Tag::slugify(trim($this->familyName));
            $username .= '-';
        }
        $now = new \DateTime();
        $emailName = explode('@', $this->email)[0];
        $username .= $emailName;
        $username .= $now->format('-dmY');
        $this->user->setUsername($username);
        $this->user->setEmail($this->email);
        if (empty($this->user->getPlainPassword()) && empty($this->user->getPassword())) {
            $this->user->setPlainPassword($this->email);
        }
        $this->user->setPerson($this);

        return $this->user;
    }

    public function getIndividualMemberOfOrganisation(Organisation $org)
    {
        /** @var IndividualMember $m */
        foreach ($this->individualMembers as $m) {
            if ($m->getOrganization() === $org) {
                return $m;
            }
        }

        return null;
    }

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember", mappedBy="person")
     */
    protected $individualMembers;

    public function addIndividualMember(IndividualMember $member)
    {
        $this->individualMembers->add($member);
        $member->setPerson($this);
    }

    public function removeIndividualMember(IndividualMember $member)
    {
        $this->individualMembers->removeElement($member);
        $member->setPerson(null);
    }

    /**
     * @var User|null
     * @ORM\OneToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\User\User", mappedBy="person")
     */
    protected $user;

    /**
     * @var string|null
     * @ORM\Column(type="string",nullable=true)
     */
    protected $idNumber;

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return null|string
     */
    public function getIdNumber(): ?string
    {
        return $this->idNumber;
    }

    /**
     * @param null|string $idNumber
     */
    public function setIdNumber(?string $idNumber): void
    {
        $this->idNumber = $idNumber;
    }

    /**
     * @return Collection
     */
    public function getIndividualMembers(): Collection
    {
        return $this->individualMembers;
    }

    /**
     * @param Collection $individualMembers
     */
    public function setIndividualMembers($individualMembers): void
    {
        $this->individualMembers = $individualMembers;
    }

}
