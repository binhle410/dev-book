<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Person;

use \Bean\Component\Person\Model\Person as PersonModel;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="person__person")
 */
class Person extends PersonModel {
	/**
	 * @var User|null
	 * @ORM\OneToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\User\User", mappedBy="person")
	 */
	protected $user;
	
	/**
	 * @var string|null
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected $name;
	
	/**
	 * @var \DateTime|null
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	protected $birthDate;
	
	/**
	 * @var string|null
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected $givenName;
	
	/**
	 * @var string|null
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected $familyName;
	
	/**
	 * @return User|null
	 */
	public function getUser(): ?User {
		return $this->user;
	}
	
	/**
	 * @param User|null $user
	 */
	public function setUser(?User $user): void {
		$this->user = $user;
	}
	
}