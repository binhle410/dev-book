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
	
	protected $name;
	protected $description;
	protected $contentUrl;
	
	public function getHost() {
		return 'https://www.sunrise.vn';
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
