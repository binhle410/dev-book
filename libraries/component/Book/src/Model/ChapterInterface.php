<?php
declare(strict_types = 1);

namespace Bean\Component\Book\Model;

use Bean\Component\CreativeWork\Model\CreativeWorkInterface;

interface ChapterInterface extends CreativeWorkInterface {
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
}