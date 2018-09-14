<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Organization\IoC\GroupIndividualAwareInterface;
use Bean\Component\Organization\IoC\IndividualMemberAwareInterface;
use Bean\Component\Organization\IoC\OrganizationAwareInterface;

interface IndividualMemberGroupInterface extends OrganizationAwareInterface, GroupIndividualAwareInterface {
	/**
	 * @param OrganizationInterface|null $organization
	 */
	public function setOrganization(?OrganizationInterface $organization): void;
}
