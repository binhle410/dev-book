<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\System;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="system__module")
 */
class SystemModule {
	
	public const MODULE_SYSTEM_CONFIG = 'SYSTEM_CONFIG';
	
	public static function getModuleList(): array {
		return [
			self::MODULE_SYSTEM_CONFIG => self::MODULE_SYSTEM_CONFIG
		];
	}
	
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
	
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\AccessControl\ACEntry", mappedBy="module", cascade={"persist","merge"}, orphanRemoval=true)
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
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $name;
	
	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @param string $name
	 */
	public function setName(string $name): void {
		$this->name = $name;
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
}