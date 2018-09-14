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
