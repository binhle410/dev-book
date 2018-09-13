<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Person\Model\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__individual_member_group__groups_individuals")
 */
class GroupIndividual {
	
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
	
	/**
	 * @var IndividualGroup
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualGroup", inversedBy="groupIndividuals")
	 * @ORM\JoinColumn(name="id_group", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $group;
	
	/**
	 * @var IndividualMember
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember", inversedBy="groupIndividuals")
	 * @ORM\JoinColumn(name="id_member", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $member;
	
	/**
	 * @return IndividualGroup
	 */
	public function getGroup(): IndividualGroup {
		return $this->group;
	}
	
	/**
	 * @param IndividualGroup $group
	 */
	public function setGroup(IndividualGroup $group): void {
		$this->group = $group;
	}
	
	/**
	 * @return IndividualMember
	 */
	public function getMember(): IndividualMember {
		return $this->member;
	}
	
	/**
	 * @param IndividualMember $member
	 */
	public function setMember(IndividualMember $member): void {
		$this->member = $member;
	}
	
	
}
