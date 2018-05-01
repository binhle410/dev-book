<?php
declare(strict_types = 1);

namespace Bean\Component\Book\Model;

use Bean\Component\CreativeWork\Model\CreativeWork;

/**
 * NOT part of schema.org
 * Class Page
 * @package Bean\Component\Book\Model
 */
class Page extends CreativeWork  {
	
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

}