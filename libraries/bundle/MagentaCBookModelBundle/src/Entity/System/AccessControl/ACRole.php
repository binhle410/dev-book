<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;

/**
 * @ORM\Entity()
 * @ORM\Table(name="access__role")
 */
class ACRole {
	
	/**
	 * @var int|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @return int|null
	 */
	public function getId(): ?int {
		return $this->id;
	}
	
	function __construct() {
		$this->entries           = new ArrayCollection();
		$this->individualMembers = new ArrayCollection();
	}
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember", mappedBy="role", cascade={"persist","merge"})
	 */
	protected $individualMembers;
	
	public function addIndividualMember(IndividualMember $member) {
		$this->individualMembers->add($member);
		$member->setRole($this);
	}
	
	public function removeIndividualMember(IndividualMember $member) {
		$this->individualMembers->removeElement($member);
		$member->setRole(null);
	}
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACEntry", mappedBy="role", cascade={"persist","merge"}, orphanRemoval=true)
	 */
	protected $entries;
	
	/**
	 * @var Organisation
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="roles", cascade={"persist","merge"})
	 * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $organisation;
	
	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;
	
	/**
	 * @return Collection
	 */
	public function getEntries(): Collection {
		return $this->entries;
	}
	
	/**
	 * @param Collection $entries
	 */
	public function setEntries(Collection $entries): void {
		$this->entries = $entries;
	}
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}
	
	/**
	 * @return Collection
	 */
	public function getIndividualMembers(): Collection {
		return $this->individualMembers;
	}
	
	/**
	 * @param Collection $individualMembers
	 */
	public function setIndividualMembers(Collection $individualMembers): void {
		$this->individualMembers = $individualMembers;
	}
	
	/**
	 * @return Organisation
	 */
	public function getOrganisation(): Organisation {
		return $this->organisation;
	}
	
	/**
	 * @param Organisation $organisation
	 */
	public function setOrganisation(Organisation $organisation): void {
		$this->organisation = $organisation;
	}
}
