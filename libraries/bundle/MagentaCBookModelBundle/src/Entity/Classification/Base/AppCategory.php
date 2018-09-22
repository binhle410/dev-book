<?php
declare(strict_types = 1);

namespace Magenta\Bundle\CBookModelBundle\Entity\Classification\Base;

use Bean\Component\Organization\IoC\OrganizationAwareInterface;
use Bean\Component\Organization\Model\OrganizationInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Category;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Context;
use Sonata\ClassificationBundle\Entity\BaseCategory;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\Validator\Constraints as Assert;

/** @ORM\MappedSuperclass */
class AppCategory extends BaseCategory implements OrganizationAwareInterface {
	
	/**
	 * @var integer|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * // Serializer\Groups(groups={"sonata_api_read", "sonata_api_write", "sonata_search"})
	 */
	protected $id;
	
	public function __construct() {
		parent::__construct();
		$this->createdAt = new \DateTime();
		$this->enabled = true;
	}
	
	/**
	 * @ORM\OneToMany(
	 *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Classification\Category",
	 *     mappedBy="parent", cascade={"persist"}, orphanRemoval=true
	 * )
	 * @ORM\OrderBy({"position"="ASC"})
	 *
	 * @var Category[]
	 */
	protected $children;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="categories")
	 * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $organisation;
	
	/**
	 * @ORM\ManyToOne(
	 *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Classification\Category",
	 *     inversedBy="children", cascade={"persist", "refresh", "merge", "detach"}
	 * )
	 * @ORM\JoinColumn(name="id_parent", referencedColumnName="id", onDelete="CASCADE")
	 *
	 * @var Category
	 */
	protected $parent;
	
	/**
	 * @ORM\ManyToOne(
	 *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Classification\Context",
	 *     cascade={"persist"}
	 * )
	 * @ORM\JoinColumn(name="context", referencedColumnName="id", nullable=false)
	 * @Assert\NotNull()
	 *
	 * @var Context
	 */
	protected $context;
	
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @var boolean|null
	 * @ORM\Column(type="boolean",options={"default":true})
	 */
	protected $public = true;
	
	final public function setMedia(MediaInterface $media = null) {
		parent::setMedia($media);
	}
	
	/**
	 * @ORM\PrePersist
	 */
	public function prePersist(): void {
		parent::prePersist();
		if( ! empty($this->parent)) {
			$this->organisation = $this->parent->getOrganisation();
		}
	}
	
	/**
	 * @ORM\PreUpdate
	 */
	public function preUpdate(): void {
		parent::preUpdate();
	}
	
	public function getOrganization(): ?OrganizationInterface {
		return $this->organisation;
	}


	
	/**
	 * @param mixed $organisation
	 */
	public function setOrganisation($organisation): void {
		$this->organisation = $organisation;
	}
	
	/**
	 * @return mixed
	 */
	public function getOrganisation() {
		return $this->organisation;
	}
	
	public function setOrganization(?OrganizationInterface $organization) {
		$this->organisation;
	}
	
	/**
	 * @return bool|null
	 */
	public function isPublic(): ?bool {
		return $this->public;
	}
	
	/**
	 * @param bool|null $public
	 */
	public function setPublic(?bool $public): void {
		$this->public = $public;
	}
}
