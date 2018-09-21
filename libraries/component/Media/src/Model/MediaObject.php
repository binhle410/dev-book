<?php
declare(strict_types = 1);

namespace Bean\Component\Media\Model;

use Bean\Component\CreativeWork\Model\CreativeWork;

abstract class MediaObject extends CreativeWork implements MediaObjectInterface {
	/**
	 * Actual bytes of the media object, for example the image file or video file.
	 * @var string|null
	 */
	protected $contentUrl;
	
	/**
	 * @return null|string
	 */
	public function getContentUrl(): ?string {
		return $this->contentUrl;
	}
	
	/**
	 * @param null|string $contentUrl
	 */
	public function setContentUrl(?string $contentUrl): void {
		$this->contentUrl = $contentUrl;
	}
	
}
