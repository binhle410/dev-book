<?php
declare(strict_types=1);

namespace Bean\Component\Book\Model;

use Bean\Component\CreativeWork\Model\CreativeWork;

class Book extends CreativeWork implements BookInterface {
	/**
	 * Indicates whether the book is an abridged edition.
	 * @var boolean
	 */
	protected $abridged;
	
	/**
	 * The edition of the book.
	 * @var string
	 */
	protected $bookEdition;
	
	/**
	 * The format of the book.
	 * @var string
	 */
	protected $bookFormat;
	
	/**
	 * The ISBN-13 of the book.
	 * @var string
	 */
	protected $isbn;
	
	/**
	 * The number of pages in the book.
	 * @var integer
	 */
	protected $numberOfPages;
	
	public function isAbridged(): bool {
		return $this->abridged;
	}
	
	public function setAbridged(bool $abridged): void {
		$this->abridged = $abridged;
	}
	
	public function getBookEdition(): string {
		return $this->bookEdition;
	}
	
	public function setBookEdition(string $bookEdition): void {
		$this->bookEdition = $bookEdition;
	}
	
	public function getBookFormat(): string {
		return $this->bookFormat;
	}
	
	public function setBookFormat(string $bookFormat): void {
		$this->bookFormat = $bookFormat;
	}
	
	public function getIsbn(): string {
		return $this->isbn;
	}
	
	public function setIsbn(string $isbn): void {
		$this->isbn = $isbn;
	}
	
	public function getNumberOfPages(): int {
		return $this->numberOfPages;
	}
	
	public function setNumberOfPages(int $numberOfPages): void {
		$this->numberOfPages = $numberOfPages;
	}
}