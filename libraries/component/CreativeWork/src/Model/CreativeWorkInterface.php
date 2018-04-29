<?php
declare(strict_types=1);

namespace Bean\Component\CreativeWork\Model;

use Bean\Component\Thing\Model\ThingInterface;

interface CreativeWorkInterface extends ThingInterface
{
	/**
	 * @return string
	 */
	public function getHeadline();
	
	/**
	 * @param string $headline
	 */
	public function setHeadline($headline): void;

}