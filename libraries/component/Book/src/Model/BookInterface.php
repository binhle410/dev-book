<?php
declare(strict_types = 1);

namespace Bean\Component\Book\Model;

use Bean\Component\CreativeWork\Model\CreativeWorkInterface;

interface BookInterface extends CreativeWorkInterface {
	
	public function isAbridged(): ?bool;
	
	public function setAbridged(bool $abridged): void;
	
	public function getBookEdition(): ?string;
	
	public function setBookEdition(string $bookEdition): void;
	
	public function getBookFormat(): ?string;
	
	public function setBookFormat(string $bookFormat): void;
	
	public function getIsbn(): ?string;
	
	public function setIsbn(string $isbn): void;
	
	public function getNumberOfPages(): ?int;
	
	public function setNumberOfPages(int $numberOfPages): void;
}
