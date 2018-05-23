<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Organisation;

use Bean\Component\Book\Model\Chapter as ChapterModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="organisation__book__chapter")
 */
class OrgChapter extends ChapterModel {
	
	function __construct() {
		parent::__construct();
		$this->locale = 'en';
	}
	
	/**
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\OrgBook", inversedBy="chapters")
	 * @ORM\JoinColumn(name="id_book", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $book;
	
	/**
	 * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\OrgChapter", cascade={"persist","merge"}, mappedBy="parentChapter")
	 */
	protected $subChapters;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\OrgChapter", inversedBy="subChapters")
	 * @ORM\JoinColumn(name="id_parent_chapter", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $parentChapter;
}