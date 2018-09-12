<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Person\Model\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Book\BookCategoryGroup;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Collection;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__member_group")
 */
class MemberGroup {
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
		$this->groupMembers    = new ArrayCollection();
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
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\GroupMember", mappedBy="group")
	 */
	protected $groupMembers;
	
	public function addGroupMember(GroupMember $gm) {
		$this->groupMembers->add($gm);
		$gm->setGroup($this);
	}
	
	public function removeGroupMember(GroupMember $gm) {
		$this->groupMembers->removeElement($gm);
		$gm->setGroup(null);
	}
	
	
	/**
	 * @var Organisation
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="memberGroups")
	 * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $organisation;
	
	/**
	 * @var string
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $name;
	
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
}
