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
	private $baseUrl = 'https://www.sunrise.vn';
	private $contentUrlPrefix;
	
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
	protected $link;
	
	/**
	 * @return string
	 */
	public function getBaseUrl(): string {
		return $this->baseUrl;
	}
	
	/**
	 * @param string $baseUrl
	 */
	public function setBaseUrl(string $baseUrl): void {
		$this->baseUrl = $baseUrl;
	}
	
	/**
	 * @return mixed
	 */
	public function getContentUrlPrefix() {
		return $this->contentUrlPrefix;
	}
	
	/**
	 * @param mixed $contentUrlPrefix
	 */
	public function setContentUrlPrefix($contentUrlPrefix): void {
		$this->contentUrlPrefix = $contentUrlPrefix;
	}
	
	/**
	 * @param mixed $link
	 */
	public function setLink($link): void {
		$this->link = $link;
	}
	
	public function getLink() {
		if(empty($this->link)) {
			if(strlen($this->contentUrlPrefix) === 1) {
				$prefix = '';
			} else {
				$prefix = $this->contentUrlPrefix;
			}
			
			$this->link = $this->getBaseUrl() . $prefix . $this->contentUrl;
		}
		
		return $this->link;
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
