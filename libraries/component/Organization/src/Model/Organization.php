<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Thing\Model\Thing;

class Organization extends Thing implements OrganizationInterface {
	
	/**
	 * @var \DateTime|null
	 */
	protected $foundingDate;
	
	/**
	 * NOT completely the same as members of schema.org
	 * @var \Countable|\IteratorAggregate|\ArrayAccess|array|null
	 */
	protected $members;
	
	/**
	 * @return array|\ArrayAccess|\Countable|\IteratorAggregate|null
	 */
	public function getMembers() {
		return $this->members;
	}
	
	/**
	 * @param array|\ArrayAccess|\Countable|\IteratorAggregate|null $members
	 */
	public function setMembers($members): void {
		$this->members = $members;
	}
	
	/**
	 * @return \DateTime|null
	 */
	public function getFoundingDate(): ?\DateTime {
		return $this->foundingDate;
	}
	
	/**
	 * @param \DateTime|null $foundingDate
	 */
	public function setFoundingDate(?\DateTime $foundingDate): void {
		$this->foundingDate = $foundingDate;
	}
}
