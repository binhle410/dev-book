<?php
/**
 * Created by PhpStorm.
 * User: Binh
 * Date: 5/28/2018
 * Time: 11:51 AM
 */

namespace Bean\Component\Book\Model;

use Bean\Component\CreativeWork\Model\CreativeWorkInterface;

interface ChapterInterface extends CreativeWorkInterface {
	public function setPartOf(CreativeWorkInterface $partOf): void;
	
	public function getListNumber($siblings = []);
	
	/**
	 * @param ChapterInterface|null $parentChapter
	 */
	public function setParentChapter(?ChapterInterface $parentChapter): void;
	
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
	 * @return BookInterface|null
	 */
	public function getBook(): ?BookInterface;
	
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
	 * @return \Bean\Component\Book\Model\ChapterInterface|null
	 */
	public function getParentChapter(): ?ChapterInterface;
}
