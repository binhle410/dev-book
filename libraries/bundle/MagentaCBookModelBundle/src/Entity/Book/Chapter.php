<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Book;

use Bean\Component\Book\Model\Chapter as ChapterModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="book__chapter")
 */
class Chapter extends ChapterModel {
	
	function __construct() {
		parent::__construct();
		$this->locale = 'en';
		$this->subChapters = new ArrayCollection();
	}
	
	/**
	 * @ORM\ManyToOne(targetEntity="Book", inversedBy="chapters")
	 * @ORM\JoinColumn(name="id_book", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $book;
	
	/**
	 * @ORM\OneToMany(targetEntity="Chapter", cascade={"persist","merge"}, mappedBy="parentChapter")
	 * @ORM\OrderBy({"position"="ASC"})
	 */
	protected $subChapters;
	
	protected function removeElementFromArrayProperty($element, $prop) {
		if($prop === 'subChapters'){
			$this->subChapters->removeElement($element);
			$element->setParentChapter(null);
			return true;
		}
		return parent::removeElementFromArrayProperty($element, $prop);
	}
	
	/**
	 * @ORM\ManyToOne(targetEntity="Chapter", inversedBy="subChapters")
	 * @ORM\JoinColumn(name="id_parent_chapter", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $parentChapter;
}
