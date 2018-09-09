<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Book;

use Bean\Component\Book\Model\Chapter as ChapterModel;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="book__chapter")
 */
class Chapter extends ChapterModel {
	
	function __construct() {
		parent::__construct();
		$this->locale = 'en';
	}
	
	/**
	 * @ORM\ManyToOne(targetEntity="Book", inversedBy="chapters")
	 * @ORM\JoinColumn(name="id_book", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $book;
	
	/**
	 * @ORM\OneToMany(targetEntity="Chapter", cascade={"persist","merge"}, mappedBy="parentChapter")
	 */
	protected $subChapters;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Chapter", inversedBy="subChapters")
	 * @ORM\JoinColumn(name="id_parent_chapter", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $parentChapter;
}
