<?php
declare(strict_types=1);

namespace Bean\Component\Thing\Model;

class Thing implements ThingInterface {
	
	protected $id;
	
	/**
	 * The name of the item.
	 * @var string
	 */
	protected $name;
	
	/**
	 * A description of the item.
	 * @var string
	 */
	protected $description;
	
	public function getId() {
		return $this->id;
	}
	
	public function getName(): string {
		return $this->name;
	}
	
	public function setName(string $name): void {
		$this->name = $name;
	}
	
	/**
	 * @return string
	 */
	public function getDescription(): string {
		return $this->description;
	}
	
	/**
	 * @param string $description
	 */
	public function setDescription(string $description): void {
		$this->description = $description;
	}
	
	
}