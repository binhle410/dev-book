<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Organization\Model\IndividualMember as MemberModel;

use Bean\Component\Person\Model\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Collection;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACRole;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__individual_member")
 */
class IndividualMember extends MemberModel {
	
	/**
	 * @var int|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	public function __construct() {
		parent::__construct();
		$this->groupIndividuals = new ArrayCollection();
		$this->enabled          = true;
	}
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\GroupIndividual", mappedBy="member")
	 */
	protected $groupIndividuals;
	
	public function addGroupIndividual(GroupIndividual $gm) {
		$this->groupIndividuals->add($gm);
		$gm->setMember($this);
	}
	
	public function removeGroupIndividual(GroupIndividual $gm) {
		$this->groupIndividuals->removeElement($gm);
		$gm->setMember(null);
	}
	
	/**
	 * @var Organisation
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="members")
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
	 * @ORM\Column(type="string",nullable=true)
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
	public function isContactable(): bool {
		return $this->contactable;
	}
	
	/**
	 * @param bool $contactable
	 */
	public function setContactable(bool $contactable): void {
		$this->contactable = $contactable;
	}
	
	/**
	 * @return null|string
	 */
	public function getCode(): ?string {
		return $this->code;
	}
	
	/**
	 * @param null|string $code
	 */
	public function setCode(?string $code): void {
		$this->code = $code;
	}
	
	/**
	 * @return null|string
	 */
	public function getPin(): ?string {
		return $this->pin;
	}
	
	/**
	 * @param null|string $pin
	 */
	public function setPin(?string $pin): void {
		$this->pin = $pin;
	}
	
	/**
	 * @return null|string
	 */
	public function getEmail(): ?string {
		return $this->email;
	}
	
	/**
	 * @param null|string $email
	 */
	public function setEmail(?string $email): void {
		$this->email = $email;
	}
	
	/**
	 * @return ACRole|null
	 */
	public function getRole(): ?ACRole {
		return $this->role;
	}
	
	/**
	 * @param ACRole|null $role
	 */
	public function setRole(?ACRole $role): void {
		$this->role = $role;
	}
}
