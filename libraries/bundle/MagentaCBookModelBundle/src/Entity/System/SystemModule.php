<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\System;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;
use Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACEntry;
use Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACRole;

/**
 * @ORM\Entity
 * @ORM\Table(name="system__module")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * ORM\DiscriminatorMap({"person" = "Person", "employee" = "Employee"})
 */
abstract class SystemModule {
	
	/**
	 * @var int|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	
	function __construct() {
		$this->acEntries = new ArrayCollection();
	}
	
	public function getRolesWithPermission($permission) {
		$permission = strtoupper($permission);
		$roles      = [];
		/** @var ACEntry $ace */
		foreach($this->acEntries as $ace) {
			if($ace->getPermission() === $permission) {
				$roles[] = $ace->getRole();
			}
		}
		
		return $roles;
	}
	
	public function isUserGranted(IndividualMember $member, $permission, $object, $class): ?bool {
		if(empty($member)) {
			return false;
		}
		
		return $this->isRoleGranted($permission, $member->getRole());
	}
	
	public function isRoleGranted($permission, ACRole $role) {
		$permission = strtoupper($permission);
		$entries    = $role->getEntries();
		/** @var ACEntry $entry */
		foreach($entries as $entry) {
			if($entry->getPermission() === $permission && $entry->getModule() === $this) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\AccessControl\ACEntry", mappedBy="module", cascade={"persist","merge"}, orphanRemoval=true)
	 */
	protected $acEntries;
	
	/**
	 * @var System
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\System", inversedBy="modules", cascade={"persist","merge"})
	 * @ORM\JoinColumn(name="id_system", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $system;
	
	/**
	 * @return int|null
	 */
	public function getId(): ?int {
		return $this->id;
	}
	
	/**
	 * @return System
	 */
	public function getSystem(): System {
		return $this->system;
	}
	
	/**
	 * @param System $system
	 */
	public function setSystem(System $system): void {
		$this->system = $system;
	}
	
	/**
	 * @return Collection
	 */
	public function getAcEntries(): Collection {
		return $this->acEntries;
	}
	
	/**
	 * @param Collection $acEntries
	 */
	public function setAcEntries(Collection $acEntries): void {
		$this->acEntries = $acEntries;
	}
	
}
