<?php
declare(strict_types = 1);

namespace Bean\Component\Page\Model;

use Bean\Component\CreativeWork\Model\CreativeWork;

/**
 * NOT part of schema.org
 * Class Page
 * @package Bean\Component\Page\Model
 */
class Page extends CreativeWork implements PageInterface {
	/**
	 * NOT part of schema.org
	 * @var string|null;
	 */
	protected $pageNumber;
	
	/**
	 * @return null|string
	 */
	public function getPageNumber(): ?string {
		return $this->pageNumber;
	}
	
	/**
	 * @param null|string $pageNumber
	 */
	public function setPageNumber(?string $pageNumber): void {
		$this->pageNumber = $pageNumber;
	}
}
