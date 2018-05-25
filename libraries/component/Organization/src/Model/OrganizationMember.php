<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Person\Model\Person;
use Bean\Component\Thing\Model\Thing;

class OrganizationMember {
	
	/**
	 * @var mixed
	 */
	protected $id;
	
	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @var Organization|null
	 */
	protected $organization;
	
	/**
	 * @var Person|null
	 */
	protected $person;
	
	/**
	 * @return Organization|null
	 */
	public function getOrganization(): ?Organization {
		return $this->organization;
	}
	
	/**
	 * @param Organization|null $organization
	 */
	public function setOrganization(?Organization $organization): void {
		$this->organization = $organization;
	}
	
	/**
	 * @return Person|null
	 */
	public function getPerson(): ?Person {
		return $this->person;
	}
	
	/**
	 * @param Person|null $person
	 */
	public function setPerson(?Person $person): void {
		$this->person = $person;
	}
	
}