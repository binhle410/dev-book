<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Organization\Model\IndividualMemberGroup;
use Bean\Component\Person\Model\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Collection;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__individual_member_group")
 */
class IndividualGroup extends IndividualMemberGroup {
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
	
	public function __construct() {
		parent::__construct();
		$this->groupIndividuals = new ArrayCollection();
		$this->members = new ArrayCollection();
	}

//	/**
//	 * @var \Doctrine\Common\Collections\Collection
//	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Book\BookCategoryGroup", mappedBy="group")
//	 */
//	protected $bookCategoryGroups;
//
//	public function addBookCategoryGroup(BookCategoryGroup $bkg) {
//		$this->bookCategoryGroups->add($bkg);
//		$bkg->setGroup($this);
//	}
//
//	public function removeBookCategoryGroup(BookCategoryGroup $bkg) {
//		$this->bookCategoryGroups->removeElement($bkg);
//		$bkg->setGroup(null);
//	}
	
	/**
	 * @var Collection
	 * @ORM\ManyToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember", mappedBy="groups")
	 */
	protected $members;
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\GroupIndividual", mappedBy="individualMemberGroup")
	 */
	protected $groupIndividuals;
	
	/**
	 * @var Organisation
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="memberGroups")
	 * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $organization;
	
	/**
	 * @var integer|null
	 * @ORM\Column(type="integer", nullable=true, options={"default":0})
	 */
	protected $position = 0;
	
	/**
	 * @return Collection
	 */
	public function getMembers(): Collection {
		return $this->members;
	}
	
	/**
	 * @param Collection $members
	 */
	public function setMembers(Collection $members): void {
		$this->members = $members;
	}
	
	/**
	 * @return int|null
	 */
	public function getPosition(): ?int {
		return $this->position;
	}
	
	/**
	 * @param int|null $position
	 */
	public function setPosition(?int $position): void {
		$this->position = $position;
	}
	
}
