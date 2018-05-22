<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\User;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user__user")
 */
class User extends AbstractUser {
	/**
	 * @var int|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @param string $firstName
	 */
	public function setFirstName(string $firstName): void {
		$this->name      = $firstName . ' ' . $this->lastName;
		$this->firstName = $firstName;
	}
	
	/**
	 * @param string $lastName
	 */
	public function setLastName(string $lastName): void {
		$this->name     = $this->firstName . ' ' . $lastName;
		$this->lastName = $lastName;
	}
	
	/**
	 * @var \DateTime|null
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $dob;
	
	/**
	 * @var string|null
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected $name;
	
	/**
	 * @var string|null
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected $firstName;
	
	/**
	 * @var string|null
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected $lastName;
	
	/**
	 * @return null|string
	 */
	public function getName(): ?string {
		return $this->name;
	}
	
	/**
	 * @return null|string
	 */
	public function getFirstName(): ?string {
		return $this->firstName;
	}
	
	/**
	 * @return null|string
	 */
	public function getLastName(): ?string {
		return $this->lastName;
	}
	
	/**
	 * @return \DateTime|null
	 */
	public function getDob(): ?\DateTime {
		return $this->dob;
	}
	
	/**
	 * @param \DateTime|null $dob
	 */
	public function setDob(?\DateTime $dob): void {
		$this->dob = $dob;
	}
	
	
}