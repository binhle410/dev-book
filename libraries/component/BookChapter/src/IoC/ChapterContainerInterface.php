<?php
declare(strict_types = 1);

namespace Bean\Component\Book\IoC;

use Bean\Component\Book\Model\ChapterInterface;

interface ChapterContainerInterface {
	
	/**
	 * @return \Countable|\IteratorAggregate|\ArrayAccess|array|null
	 */
	public function getChapters();
	
	/**
	 * @param \Countable|\IteratorAggregate|\ArrayAccess|array|null $chapters
	 *
	 * @return mixed
	 */
	public function setChapters($chapters);
	
	/**
	 * @param ChapterInterface $chapter
	 *
	 * @return mixed
	 */
	public function addChapter(ChapterInterface $chapter);
	
	/**
	 * @param ChapterInterface $chapter
	 *
	 * @return mixed
	 */
	public function removeChapter(ChapterInterface $chapter);
	
}
