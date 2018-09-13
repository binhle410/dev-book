<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

interface OrganizationInterface {
	/**
	 * @return array|\ArrayAccess|\Countable|\IteratorAggregate|null
	 */
	public function getMembers();
	
	/**
	 * @param array|\ArrayAccess|\Countable|\IteratorAggregate|null $members
	 */
	public function setMembers($members): void;
	
	/**
	 * @return \DateTime|null
	 */
	public function getFoundingDate(): ?\DateTime;
	
	/**
	 * @param \DateTime|null $foundingDate
	 */
	public function setFoundingDate(?\DateTime $foundingDate): void;
}
