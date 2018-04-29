<?php
declare(strict_types=1);

namespace Bean\Component\Thing\Model;

/**
 * Class Thing: The most generic type of item.
 * @package Bean\Component\Thing\Model
 */
interface ThingInterface {
	/**
	 * @return mixed
	 */
	public function getId();
	
	/**
	 * @return string
	 */
	public function getName(): string;
	
	/**
	 * @param string $name
	 */
	public function setName(string $name): void;
}