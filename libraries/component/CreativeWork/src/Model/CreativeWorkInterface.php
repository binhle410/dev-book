<?php
declare(strict_types=1);

namespace Bean\Component\CreativeWork\Model;

interface CreativeWorkInterface
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