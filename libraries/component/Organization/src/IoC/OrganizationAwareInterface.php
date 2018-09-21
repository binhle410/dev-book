<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\IoC;

use Bean\Component\Organization\Model\OrganizationInterface;

/**
 * Interface OrganizationAwareInterface
 * This is not part of schema.org
 * @package Bean\Component\Organization
 */
interface OrganizationAwareInterface {
	public function getOrganization(): ?OrganizationInterface;
	public function setOrganization(?OrganizationInterface $organization);
}
