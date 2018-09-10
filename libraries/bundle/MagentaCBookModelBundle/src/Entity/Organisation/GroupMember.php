<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Person\Model\Person;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__member_groups_members")
 */
class GroupMember {
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
	 * @var MemberGroup
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\MemberGroup", inversedBy="groupMembers")
	 * @ORM\JoinColumn(name="id_group", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $group;
	
	/**
	 * @var OrganisationMember
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\OrganisationMember", inversedBy="groupMembers")
	 * @ORM\JoinColumn(name="id_member", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $member;
	
	/**
	 * @return MemberGroup
	 */
	public function getGroup(): MemberGroup {
		return $this->group;
	}
	
	/**
	 * @param MemberGroup $group
	 */
	public function setGroup(MemberGroup $group): void {
		$this->group = $group;
	}
	
	/**
	 * @return OrganisationMember
	 */
	public function getMember(): OrganisationMember {
		return $this->member;
	}
	
	/**
	 * @param OrganisationMember $member
	 */
	public function setMember(OrganisationMember $member): void {
		$this->member = $member;
	}
	
	
}
