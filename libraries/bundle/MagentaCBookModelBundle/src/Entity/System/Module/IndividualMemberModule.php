<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\System\Module;

use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACEntry;
use Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACModuleInterface;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\System\SystemModule;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="system__module__individual_member")
 */
class IndividualMemberModule extends SystemModule implements ACModuleInterface {
	
	public function isUserGranted(IndividualMember $member, $permission, $object, $class): ?bool {
		if( ! $this->isClassSupported($class)) {
			return null;
		}
		return parent::isUserGranted($member, $permission, $object, $class);
		
	}
	
	public function isClassSupported(string $class): bool {
		return $class === IndividualMember::class;
	}
	
	public function getSupportedModuleActions(): array {
		return ACEntry::getSupportedActions();
	}
	
	public function getModuleName(): string {
		return 'User Management';
	}
	
	public function getModuleCode(): string {
		return 'USER';
	}
}
