<?php
namespace Magenta\Bundle\CBookModelBundle\Entity\System;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="system__system")
 */
class System {
	/**
	 * @var string
	 * @ORM\Id
	 * @ORM\Column(type="string", length=180)
	 */
	protected $id = 'magenta.cbook';
	/**
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\SystemModule", mappedBy="system", cascade={"persist","merge"}, orphanRemoval=true)
	 */
	protected $modules;
	
	/**
	 * @var boolean
	 */
	protected $enabled;
	/**
	 * @var Collection
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", mappedBy="system", cascade={"persist","merge"}, orphanRemoval=true)
	 */
	protected $organisations;

	
	/**
	 * @param string $id
	 */
	public function setId(string $id): void {
		$this->id = $id;
	}
	
	/**
	 * @return Collection
	 */
	public function getModules(): Collection {
		return $this->modules;
	}
	
	/**
	 * @param Collection $modules
	 */
	public function setModules(Collection $modules): void {
		$this->modules = $modules;
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
	 * @return Collection
	 */
	public function getOrganisations(): Collection {
		return $this->organisations;
	}
	
	/**
	 * @param Collection $organisations
	 */
	public function setOrganisations(Collection $organisations): void {
		$this->organisations = $organisations;
	}
	
}
