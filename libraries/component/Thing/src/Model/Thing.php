<?php
declare(strict_types = 1);

namespace Bean\Component\Thing\Model;

/**
 * Class Thing: The most generic type of item.
 * @package Bean\Component\Thing\Model
 */
abstract class Thing implements ThingInterface {
	
	protected $id;
	
	/**
	 * NOT part of schema.org
	 * @var boolean|null
	 */
	protected $enabled;
	
	/**
	 * The name of the item.
	 * @var string|null
	 */
	protected $name;
	
	/**
	 * A description of the item.
	 * @var string|null
	 */
	protected $description;
	
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getName(): ?string {
		return $this->name;
	}
	
	/**
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
	}
	
	/**
	 * @return string
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
	
	/**
	 * @param string $description
	 */
	public function setDescription(string $description): void {
		$this->description = $description;
	}
	
	/**
	 * @return bool|null
	 */
	public function getEnabled(): ?bool {
		return $this->enabled;
	}
	
	/**
	 * @param bool|null $enabled
	 */
	public function setEnabled(?bool $enabled): void {
		$this->enabled = $enabled;
	}
	
}