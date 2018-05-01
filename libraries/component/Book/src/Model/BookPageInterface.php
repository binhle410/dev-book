<?php
declare(strict_types = 1);

namespace Bean\Component\Book\Model;
use Bean\Component\CreativeWork\Model\CreativeWorkInterface;

/**
 * NOT part of schema.org
 * Class Page
 * @package Bean\Component\Book\Model
 */
interface BookPageInterface extends CreativeWorkInterface {
	/**
	 * @return BookInterface
	 */
	public function getBook(): BookInterface;
	
	/**
	 * @param BookInterface $book
	 */
	public function setBook(BookInterface $book): void;
	
	/**
	 * @return array
	 */
	public function getChapters(): array;
	
	/**
	 * @param array $chapters
	 */
	public function setChapters(array $chapters): void;
}