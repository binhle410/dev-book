<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Classification\Base;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sonata\ClassificationBundle\Entity\BaseContext;

/** @ORM\MappedSuperclass */
class AppContext extends BaseContext {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string", length=125)
	 * @ORM\GeneratedValue(strategy="NONE")
	 * // Serializer\Groups(groups={"sonata_api_read", "sonata_api_write", "sonata_search"})
	 *
	 * @var string
	 */
	protected $id;
	
	/**
	 * Get id
	 *
	 * @return int $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist(): void {
		parent::prePersist();
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate(): void {
		parent::preUpdate();
	}
}
