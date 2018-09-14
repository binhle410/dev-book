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
		$this->groupIndividuals    = new ArrayCollection();
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
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\GroupIndividual", mappedBy="group")
	 */
	protected $groupIndividuals;
	
	
	/**
	 * @var Organisation
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="memberGroups")
	 * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $organization;
	
}
