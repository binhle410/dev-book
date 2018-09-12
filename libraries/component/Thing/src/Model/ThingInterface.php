<?php
/**
 * Created by PhpStorm.
 * User: Binh
 * Date: 9/8/2018
 * Time: 8:03 PM
 */

namespace Bean\Component\Thing\Model;


/**
 * Class Thing: The most generic type of item.
 * @package Bean\Component\Thing\Model
 */
interface ThingInterface {
	public function getId();
	
	/**
	 * @return bool
	 */
	public function isEnabled(): bool;
	
	/**
	 * @param bool $enabled
	 */
	public function setEnabled($enabled): void;
	
	/**
	 * @return \DateTime
	 */
	public function getCreatedAt(): \DateTime;
	
	/**
	 * To be compatible with Sonata MediaInterface
	 * @param \DateTime $createdAt
	 */
	public function setCreatedAt(?\DateTime $createdAt = null): void;
	
	/**
	 * @return \DateTime
	 */
	public function getUpdatedAt(): \DateTime;
	
	/**
	 * To be compatible with Sonata MediaInterface
	 * @param \DateTime $updatedAt
	 */
	public function setUpdatedAt(?\DateTime $updatedAt = null): void;
	
	/**
	 * @return null|string
	 */
	public function getName(): ?string;
	
	/**
	 * To be compatible with Sonata MediaInterface
	 * @param null|string $name
	 */
	public function setName($name): void;
	
	/**
	 * @return null|string
	 */
	public function getDescription(): ?string;
	
	/**
	 * To be compatible with Sonata MediaInterface
	 * @param null|string $description
	 */
	public function setDescription($description): void;
}
