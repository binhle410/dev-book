<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Organization\IoC\IndividualMemberAwareInterface;

class GroupIndividual implements GroupIndividualInterface {
	
	/**
	 * @var IndividualMemberInterface|null
	 */
	protected $individualMember;
	
	/**
	 * @var IndividualMemberGroup|null
	 */
	protected $individualMemberGroup;
	
	/**
	 * @return IndividualMemberInterface|null
	 */
	public function getIndividualMember(): ?IndividualMemberInterface {
		return $this->individualMember;
	}
	
	/**
	 * @param IndividualMemberInterface|null $individualMember
	 */
	public function setIndividualMember(?IndividualMemberInterface $individualMember): void {
		$this->individualMember = $individualMember;
	}
	
	/**
	 * @return IndividualMemberGroupInterface|null
	 */
	public function getIndividualMemberGroup(): ?IndividualMemberGroupInterface {
		return $this->individualMemberGroup;
	}
	
	/**
	 * @param IndividualMemberGroupInterface|null $individualMemberGroup
	 */
	public function setIndividualMemberGroup(?IndividualMemberGroupInterface $individualMemberGroup): void {
		$this->individualMemberGroup = $individualMemberGroup;
	}
	
}
