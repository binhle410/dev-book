<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Organization\IoC\OrganizationAwareInterface;
use Bean\Component\Person\Model\Person;
use Bean\Component\Person\Model\PersonInterface;
use Bean\Component\Thing\Model\Thing;

class IndividualMember extends Thing implements IndividualMemberInterface, OrganizationAwareInterface {
	
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
	public function getOrganization(): ?OrganizationInterface {
		return $this->organization;
	}
	
	/**
	 * @param Organization|null $organization
	 */
	public function setOrganization(?OrganizationInterface $organization): void {
		$this->organization = $organization;
	}
	
	/**
	 * @return PersonInterface|null
	 */
	public function getPerson(): ?PersonInterface {
		return $this->person;
	}
	
	/**
	 * @param PersonInterface|null $person
	 */
	public function setPerson(?PersonInterface $person): void {
		$this->person = $person;
	}
	
}
