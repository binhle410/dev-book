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
 * @ORM\Table(name="system__module__system_config")
 */
class SystemConfigModule extends SystemModule implements ACModuleInterface {
	public function isUserGranted(IndividualMember $member, $permission, $object, $class): ?bool {
		if( ! $this->isClassSupported($class)) {
			return null;
		}
		
		return parent::isUserGranted($member, $permission, $object, $class);
		
	}
	
	public function isClassSupported(string $class): bool {
		return true;
	}
	
	public function getSupportedModuleActions(): array {
		return ACEntry::getSupportedActions();
	}
	
	public function getModuleName(): string {
		return 'System Config';
	}
	
	public function getModuleCode(): string {
		return 'SYSTEM_CONFIG';
	}
}
