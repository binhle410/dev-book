<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Thing\Model\Thing;

class IndividualMemberGroup extends Thing implements IndividualMemberGroupInterface {
	
	/**
	 * @var \Countable|\IteratorAggregate|\ArrayAccess|array|null
	 */
	protected $groupIndividuals;
	
	public function addGroupIndividual(GroupIndividualInterface $gm) {
		$this->addElementToArrayProperty($gm, 'groupIndividuals');
		$gm->setIndividualMemberGroup($this);
	}
	
	public function removeGroupIndividual(GroupIndividualInterface $gm) {
		$this->removeElementFromArrayProperty($gm, 'groupIndividuals');
		$gm->setIndividualMemberGroup(null);
	}
	
	/**
	 * @var OrganizationInterface|null
	 */
	protected $organization;
	
	/**
	 * @return array|\ArrayAccess|\Countable|\IteratorAggregate|null
	 */
	public function getGroupIndividuals() {
		return $this->groupIndividuals;
	}
	
	/**
	 * @param array|\ArrayAccess|\Countable|\IteratorAggregate|null $groupIndividuals
	 */
	public function setGroupIndividuals($groupIndividuals): void {
		$this->groupIndividuals = $groupIndividuals;
	}
	
	/**
	 * @return OrganizationInterface|null
	 */
	public function getOrganization(): ?OrganizationInterface {
		return $this->organization;
	}
	
	/**
	 * @param OrganizationInterface|null $organization
	 */
	public function setOrganization(?OrganizationInterface $organization): void {
		$this->organization = $organization;
	}
}
