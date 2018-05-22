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
	protected $id = 'magenta.CBook';
	
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
	 * @return string
	 */
	public function getId(): string {
		return $this->id;
	}
	
	/**
	 * @param string $id
	 */
	public function setId(string $id): void {
		$this->id = $id;
	}
	
}