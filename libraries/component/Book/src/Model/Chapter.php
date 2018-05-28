<?php
declare(strict_types = 1);

namespace Bean\Component\Book\Model;

use Bean\Component\CreativeWork\Model\CreativeWork;
use Bean\Component\CreativeWork\Model\CreativeWorkInterface;

class Chapter extends CreativeWork implements ChapterInterface {
	
	public function setPartOf(CreativeWorkInterface $partOf): void {
		parent::setPartOf($partOf);
		if($partOf instanceof BookInterface) {
			$partOf->addChapter($this);
		} elseif($partOf instanceof ChapterInterface) {
			$partOf->addSubChapter($this);
		}
	}
	
	/**
	 * @param ChapterInterface $parentChapter
	 */
	public function setParentChapter(ChapterInterface $parentChapter): void {
		$this->book = $parentChapter->getBook();
		$this->parentChapter = $parentChapter;
	}
	
	/**
	 * NOT part of schema.org.
	 * A Chapter should belong to a Book.
	 * @var \Countable|\IteratorAggregate|\ArrayAccess|array|null
	 */
	protected $subChapters;
	
	public function addSubChapter(ChapterInterface $chapter) {
		$this->subChapters[] = $chapter;
		$chapter->setParentChapter($this);
	}
	
	/**
	 * NOT part of schema.org.
	 * A Chapter should belong to a Book.
	 * @var ChapterInterface|null
	 */
	protected $parentChapter;
	
	/**
	 * NOT part of schema.org.
	 * A Chapter should belong to a Book.
	 * @var BookInterface|null
	 */
	protected $book;
	
	/**
	 * The page on which the work ends; for example "138" or "xvi".
	 * @var string|integer
	 */
	protected $pageEnd;
	
	/**
	 * The page on which the work starts; for example "135" or "xiii".
	 * @var string|integer
	 */
	protected $pageStart;
	
	/**
	 * @return int|string
	 */
	public function getPageEnd() {
		return $this->pageEnd;
	}
	
	/**
	 * @param int|string $pageEnd
	 */
	public function setPageEnd($pageEnd): void {
		$this->pageEnd = $pageEnd;
	}
	
	/**
	 * @return int|string
	 */
	public function getPageStart() {
		return $this->pageStart;
	}
	
	/**
	 * @param int|string $pageStart
	 */
	public function setPageStart($pageStart): void {
		$this->pageStart = $pageStart;
	}
	
	/**
	 * @return BookInterface|null
	 */
	public function getBook(): ?BookInterface {
		return $this->book;
	}
	
	/**
	 * @param BookInterface $book
	 */
	public function setBook(BookInterface $book): void {
		$this->book = $book;
	}
	
	/**
	 * @return array|\ArrayAccess|null
	 */
	public function getSubChapters() {
		return $this->subChapters;
	}
	
	/**
	 * @param array|\ArrayAccess|null $subChapters
	 */
	public function setSubChapters($subChapters): void {
		$this->subChapters = $subChapters;
	}
	
	/**
	 * @return ChapterInterface|null
	 */
	public function getParentChapter(): ?ChapterInterface {
		return $this->parentChapter;
	}
}