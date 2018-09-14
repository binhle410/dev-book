<?php
namespace Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl;

interface ACModuleInterface {
	public function getModuleName(): string;
	
	public function getModuleCode(): string;
	
	public function getSupportedModuleActions(): array;
}
