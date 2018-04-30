<?php
declare(strict_types=1);

namespace Bean\Component\Book\Model;

use Bean\Component\CreativeWork\Model\CreativeWorkInterface;

interface BookInterface extends CreativeWorkInterface {
	
	/**
	 * @return bool
	 */
	public function isAbridged(): ?bool;
	
	/**
	 * @param bool $abridged
	 */
	public function setAbridged(bool $abridged): void;
	
	/**
	 * @return string
	 */
	public function getBookEdition(): ?string;
	
	/**
	 * @param string $bookEdition
	 */
	public function setBookEdition(string $bookEdition): void;
	
	/**
	 * @return string
	 */
	public function getBookFormat(): ?string;
	
	/**
	 * @param string $bookFormat
	 */
	public function setBookFormat(string $bookFormat): void;
	
	/**
	 * @return string
	 */
	public function getIsbn(): ?string;
	
	/**
	 * @param string $isbn
	 */
	public function setIsbn(string $isbn): void;
	
	/**
	 * @return int
	 */
	public function getNumberOfPages(): ?int;
	
	/**
	 * @param int $numberOfPages
	 */
	public function setNumberOfPages(int $numberOfPages): void;
}