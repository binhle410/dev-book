<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Thing\Model\ThingInterface;

interface OrganizationInterface extends ThingInterface {
	/**
	 * @return \DateTime|null
	 */
	public function getFoundingDate(): ?\DateTime;
	
	/**
	 * @param \DateTime|null $foundingDate
	 */
	public function setFoundingDate(?\DateTime $foundingDate): void;
}
