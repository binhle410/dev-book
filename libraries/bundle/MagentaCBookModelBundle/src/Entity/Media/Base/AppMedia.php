<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Media\Base;

use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Sonata\MediaBundle\Entity\BaseMedia;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/** @ORM\MappedSuperclass */
class AppMedia extends BaseMedia {
	/**
	 * @var int|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	function __construct() {
		$this->enabled = true;
	}
	
	/**
	 * @var Organisation
	 * @ORM\OneToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="logo")
	 * @ORM\JoinColumn(name="id_logo_organisation", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $logoOrganisation;
	
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
	
	
	/**
	 * @param ArrayCollection $resumeCandidates
	 */
	public function setResumeCandidates($resumeCandidates) {
		$this->resumeCandidates = $resumeCandidates;
	}
	
	/**
	 * @return Media
	 */
	public function getThumbnail() {
		return $this->thumbnail;
	}
	
	/**
	 * @param Media $thumbnail
	 */
	public function setThumbnail($thumbnail) {
		$this->thumbnail = $thumbnail;
	}
	
	/**
	 * @return User
	 */
	public function getAvatarUser() {
		return $this->avatarUser;
	}
	
	/**
	 * @param User $avatarUser
	 */
	public function setAvatarUser($avatarUser) {
		$this->avatarUser = $avatarUser;
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function getMediaH5PContent() {
		return $this->mediaH5PContent;
	}
	
	/**
	 * @param ArrayCollection $mediaH5PContent
	 */
	public function setMediaH5PContent($mediaH5PContent) {
		$this->mediaH5PContent = $mediaH5PContent;
	}
}