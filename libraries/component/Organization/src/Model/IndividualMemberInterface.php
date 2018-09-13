<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Person\Model\Person;

interface IndividualMemberInterface {
	/**
	 * @return mixed
	 */
	public function getId();
	
	/**
	 * @return Organization|null
	 */
	public function getOrganization(): ?Organization;
	
	/**
	 * @param Organization|null $organization
	 */
	public function setOrganization(?Organization $organization): void;
	
	/**
	 * @return Person|null
	 */
	public function getPerson(): ?Person;
	
	/**
	 * @param Person|null $person
	 */
	public function setPerson(?Person $person): void;
}
