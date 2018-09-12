<?php
namespace Bean\Component\Media\Model;

interface MediaObjectInterface {
	
	/**
	 * @return null|string
	 */
	public function getContentUrl(): ?string;
	
	/**
	 * @param null|string $contentUrl
	 */
	public function setContentUrl(?string $contentUrl): void;
}
