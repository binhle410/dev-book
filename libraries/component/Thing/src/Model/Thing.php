<?php
declare(strict_types = 1);

namespace Bean\Component\Thing\Model;

/**
 * Class Thing: The most generic type of item.
 * @package Bean\Component\Thing\Model
 */
abstract class Thing implements ThingInterface {
	
	protected $id;

	function __construct() {
		$this->createdAt = new \DateTime();
	}
	
	/**
	 * NOT part of schema.org
	 * @var boolean
	 */
	protected $enabled = false;
	
	/**
	 * NOT part of schema.org
	 * @var \DateTime
	 */
	protected $createdAt;
	
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
	 * @return bool
	 */
	public function isEnabled(): bool {
		return $this->enabled;
	}
	
	/**
	 * @param bool $enabled
	 */
	public function setEnabled(bool $enabled): void {
		$this->enabled = $enabled;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getCreatedAt(): \DateTime {
		return $this->createdAt;
	}
	
	/**
	 * @param \DateTime $createdAt
	 */
	public function setCreatedAt(\DateTime $createdAt): void {
		$this->createdAt = $createdAt;
	}
	
	/**
	 * @return null|string
	 */
	public function getName(): ?string {
		return $this->name;
	}
	
	/**
	 * @param null|string $name
	 */
	public function setName(?string $name): void {
		$this->name = $name;
	}
	
	/**
	 * @return null|string
	 */
	public function getDescription(): ?string {
		return $this->description;
	}
	
	/**
	 * @param null|string $description
	 */
	public function setDescription(?string $description): void {
		$this->description = $description;
	}
}