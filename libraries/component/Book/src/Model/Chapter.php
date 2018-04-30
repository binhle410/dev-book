<?php
declare(strict_types = 1);

namespace Bean\Component\Book\Model;

use Bean\Component\CreativeWork\Model\CreativeWork;

class Chapter extends CreativeWork implements ChapterInterface {
	
	/**
	 * This is not part of schema.org
	 * A Chapter belongs to a Book.
	 * @var BookInterface
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
	 * @return BookInterface
	 */
	public function getBook(): BookInterface {
		return $this->book;
	}
	
	/**
	 * @param BookInterface $book
	 */
	public function setBook(BookInterface $book): void {
		$this->book = $book;
	}
}