<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Book\Model\Book as BookModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__book")
 */
class OrgBook extends BookModel {
	
	function __construct() {
		parent::__construct();
		$this->locale = 'en';
	}
	
	/**
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\OrgChapter", cascade={"persist","merge"}, orphanRemoval=true, mappedBy="book")
	 */
	protected $chapters;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="books")
	 * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $organisation;
	
	/**
	 * @return mixed
	 */
	public function getOrganisation() {
		return $this->organisation;
	}
	
	/**
	 * @param mixed $organisation
	 */
	public function setOrganisation($organisation): void {
		$this->organisation = $organisation;
	}
}