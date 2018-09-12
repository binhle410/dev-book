<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Media\Base;

use Bean\Bundle\SonataMediaBundle\Doctrine\Orm\BaseMedia;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sonata\MediaBundle\Model\MediaInterface;

/** @ORM\MappedSuperclass */
class AppMedia extends BaseMedia implements MediaInterface {
	
	/**
	 * @var int|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	function __construct() {
		parent::__construct();
		$this->enabled = true;
	}
	
	/**
	 * @var Organisation
	 * @ORM\OneToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="logo")
	 * @ORM\JoinColumn(name="id_logo_organisation", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $logoOrganisation;
	
	/**
	 * @var string|null
	 * @ORM\Column(type="string",length=1000, nullable=true)
	 */
	protected $contentUrl;
	
	/**
	 * Get id
	 *
	 * @return int $id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return Organisation
	 */
	public function getLogoOrganisation() {
		return $this->logoOrganisation;
	}
	
	/**
	 * @param Organisation $logoOrganisation
	 */
	public function setLogoOrganisation($logoOrganisation) {
		$this->logoOrganisation = $logoOrganisation;
	}
	
}
