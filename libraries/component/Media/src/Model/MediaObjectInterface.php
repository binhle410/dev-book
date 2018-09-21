<?php
namespace Bean\Component\Media\Model;

use Bean\Component\CreativeWork\Model\CreativeWorkInterface;

interface MediaObjectInterface extends CreativeWorkInterface {
	
	/**
	 * @return null|string
	 */
	public function getContentUrl(): ?string;
	
	/**
	 * @param null|string $contentUrl
	 */
	public function setContentUrl(?string $contentUrl): void;
}
