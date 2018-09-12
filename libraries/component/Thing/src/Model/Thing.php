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
	 * NOT part of schema.org
	 * @var \DateTime|null
	 */
	protected $updatedAt;
	
	/**
	 * NOT part of schema.org
	 * A thing may have a status like DRAFT, OPEN, CLOSED, EXPIRED, ARCHIVED
	 * @var string
	 */
	protected $status;
	
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
	public function setEnabled($enabled): void {
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
	public function setCreatedAt(?\DateTime $createdAt = null): void {
		$this->createdAt = $createdAt;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt(): \DateTime {
		return $this->updatedAt;
	}
	
	/**
	 * @param \DateTime $updatedAt
	 */
	public function setUpdatedAt(?\DateTime $updatedAt = null): void {
		$this->updatedAt = $updatedAt;
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
	public function setName($name): void {
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
	public function setDescription($description): void {
		$this->description = $description;
	}
}
