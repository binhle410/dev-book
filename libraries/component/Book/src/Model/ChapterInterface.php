<?php
declare(strict_types = 1);

namespace Bean\Component\Book\Model;

use Bean\Component\CreativeWork\Model\CreativeWorkInterface;

interface ChapterInterface extends CreativeWorkInterface {
	
	public function addSubChapter(ChapterInterface $chapter);
	
	/**
	 * @return int|string
	 */
	public function getPageEnd();
	
	/**
	 * @param int|string $pageEnd
	 */
	public function setPageEnd($pageEnd): void;
	
	/**
	 * @return int|string
	 */
	public function getPageStart();
	
	/**
	 * @param int|string $pageStart
	 */
	public function setPageStart($pageStart): void;
	
	/**
	 * @return BookInterface
	 */
	public function getBook(): BookInterface;
	
	/**
	 * @param BookInterface $book
	 */
	public function setBook(BookInterface $book): void;
	
	/**
	 * @return array|\ArrayAccess|null
	 */
	public function getSubChapters();
	
	/**
	 * @param array|\ArrayAccess|null $subChapters
	 */
	public function setSubChapters($subChapters): void;
	
	/**
	 * @return \Bean\Component\Book\Model\ChapterInterface
	 */
	public function getParentChapter(): ChapterInterface;
	
	/**
	 * @param ChapterInterface $parentChapter
	 */
	public function setParentChapter(ChapterInterface $parentChapter): void;
}