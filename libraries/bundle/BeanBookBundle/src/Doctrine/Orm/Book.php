<?php

namespace Bean\Bundle\BookBundle\Doctrine\Orm;

use Bean\Component\Book\IoC\ChapterContainerInterface;
use Bean\Component\Book\Model\ChapterInterface;
use Bean\Component\Book\Model\Book as BookModel;
use Doctrine\Common\Collections\Collection;

class Book extends BookModel implements ChapterContainerInterface {
	
	/**
	 * NOT part of schema.org
	 * @var Collection
	 */
	protected $chapters;
	
	public function addChapter(ChapterInterface $chapter) {
		$this->chapters->add($chapter);
		$chapter->setBook($this);
	}
	
	public function removeChapter(ChapterInterface $chapter) {
		$this->chapters->removeElement($chapter);
		$chapter->setBook(null);
	}
	
	/**
	 * @return \Countable|\IteratorAggregate|\ArrayAccess|array|null
	 */
	public function getChapters() {
		return $this->chapters;
	}
	
	/**
	 * @param \Countable|\IteratorAggregate|\ArrayAccess|array|null $chapters
	 */
	public function setChapters($chapters): void {
		$this->chapters = $chapters;
	}
}
