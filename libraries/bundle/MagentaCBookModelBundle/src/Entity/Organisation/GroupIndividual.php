<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Organization\Model\IndividualMemberGroup;
use Bean\Component\Organization\Model\IndividualMemberGroupInterface;
use Bean\Component\Organization\Model\IndividualMemberInterface;
use Bean\Component\Person\Model\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__individual_member_group__groups_individuals")
 */
class GroupIndividual extends \Bean\Component\Organization\Model\GroupIndividual {
	
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
	protected $individualMemberGroup;
	
	/**
	 * @var IndividualMember
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember", inversedBy="groupIndividuals")
	 * @ORM\JoinColumn(name="id_member", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $individualMember;
	
	/**
	 * @return IndividualMemberGroupInterface
	 */
	public function getGroup(): IndividualMemberGroupInterface {
		return $this->getIndividualMemberGroup();
	}
	
	/**
	 * @param IndividualMemberGroupInterface $group
	 */
	public function setGroup(IndividualMemberGroupInterface $group): void {
		$this->group = $group;
	}
	
	/**
	 * @return IndividualMemberInterface
	 */
	public function getMember(): IndividualMemberInterface {
		return $this->getIndividualMember();
	}
	
	/**
	 * @param IndividualMember $member
	 */
	public function setMember(IndividualMember $member): void {
		$this->member = $member;
	}
	
	
}
