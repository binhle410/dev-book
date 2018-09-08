<?php
declare(strict_types = 1);

namespace Magenta\Bundle\CBookModelBundle\Entity\Classification\Base;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Sonata\ClassificationBundle\Entity\BaseCategory;
use Sonata\ClassificationBundle\Entity\BaseCollection;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\Validator\Constraints as Assert;

/** @ORM\MappedSuperclass */
class AppCollection extends BaseCollection {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * // Serializer\Groups(groups={"sonata_api_read", "sonata_api_write", "sonata_search"})
	 *
	 * @var int
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
	 * @ORM\ManyToOne(
	 *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Classification\Context",
	 *     cascade={"persist"}
	 * )
	 * @ORM\JoinColumn(name="context", referencedColumnName="id", nullable=false)
	 *
	 * @var Context
	 */
	protected $context;
	
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
