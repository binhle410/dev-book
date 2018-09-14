<?php
declare(strict_types = 1);

namespace Bean\Component\Page\Model;


/**
 * NOT part of schema.org
 * Class Page
 * @package Bean\Component\Page\Model
 */
interface PageInterface {
	/**
	 * @return string|null
	 */
	public function getPageNumber(): ?string;
	
	/**
	 * @param string|null $pageNumber
	 */
	public function setPageNumber(?string $pageNumber): void;
}
