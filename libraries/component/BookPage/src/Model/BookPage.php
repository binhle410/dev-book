<?php
declare(strict_types = 1);

namespace Bean\Component\Book\Model;

use Bean\Component\CreativeWork\Model\CreativeWork;
use Bean\Component\CreativeWork\Model\CreativeWorkInterface;
use Bean\Component\Page\Model\Page;

/**
 * NOT part of schema.org
 * Class Page
 * @package Bean\Component\Book\Model
 */
class BookPage extends Page {
	public function setPartOf(CreativeWorkInterface $partOf): void {
//		parent::setPartOf($partOf);
		if($partOf instanceof BookInterface) {
			$this->book = $partOf;
		}
	}
	
	/**
	 * NOT part of schema.org.
	 * A Page should belong to a Book.
	 * @var BookInterface
	 */
	protected $book;
	
	/**
	 * NOT part of schema.org
	 * @var array
	 */
	protected $chapters;
	
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
	
	/**
	 * @return array
	 */
	public function getChapters(): array {
		return $this->chapters;
	}
	
	/**
	 * @param array $chapters
	 */
	public function setChapters(array $chapters): void {
		$this->chapters = $chapters;
	}
}
