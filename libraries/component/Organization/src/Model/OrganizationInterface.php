<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

interface OrganizationInterface {
	/**
	 * @return \DateTime|null
	 */
	public function getFoundingDate(): ?\DateTime;
	
	/**
	 * @param \DateTime|null $foundingDate
	 */
	public function setFoundingDate(?\DateTime $foundingDate): void;
}
