<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Person\Model\Person;
use Bean\Component\Person\Model\PersonInterface;

interface IndividualMemberInterface {
	/**
	 * @return mixed
	 */
	public function getId();
	
	/**
	 * @return OrganizationInterface|null
	 */
	public function getOrganization(): ?OrganizationInterface;
	
	/**
	 * @param Organization|null $organization
	 */
	public function setOrganization(?OrganizationInterface $organization): void;
	
	/**
	 * @return PersonInterface|null
	 */
	public function getPerson(): ?PersonInterface;
	
	/**
	 * @param PersonInterface|null $person
	 */
	public function setPerson(?PersonInterface $person): void;
}
