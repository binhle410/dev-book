<?php
/**
 * Created by PhpStorm.
 * User: Binh
 * Date: 7/3/2018
 * Time: 9:10 PM
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
	public function setEnabled(bool $enabled): void;
	
	/**
	 * @return \DateTime
	 */
	public function getCreatedAt(): \DateTime;
	
	/**
	 * @param \DateTime $createdAt
	 */
	public function setCreatedAt(\DateTime $createdAt): void;
	
	/**
	 * @return null|string
	 */
	public function getName(): ?string;
	
	/**
	 * @param null|string $name
	 */
	public function setName(?string $name): void;
	
	/**
	 * @return null|string
	 */
	public function getDescription(): ?string;
	
	/**
	 * @param null|string $description
	 */
	public function setDescription(?string $description): void;
}