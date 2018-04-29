<?php
declare(strict_types=1);

namespace Bean\Component\CreativeWork\Model;

use Bean\Component\Thing\Model\Thing;

class CreativeWork extends Thing implements CreativeWorkInterface {
	/**
	 * Headline of the article.
	 * @var string
	 */
	protected $headline;
	
	/**
	 *    The language of the content or performance or used in an action. Please use one of the language codes from the IETF BCP 47 standard.
	 * @var string
	 */
	protected $locale;
	
	/**
	 * The subject matter of the content.
	 * @var string
	 */
	protected $about;
	
	/**
	 * Text or URL
	 * Media type, typically MIME format (see IANA site) of the content e.g. application/zip of a SoftwareApplication binary. In cases where a CreativeWork has several media type representations, 'encoding' can be used to indicate each MediaObject alongside particular fileFormat information. Unregistered or niche file formats can be indicated instead via the most appropriate URL, e.g. defining Web page or a Wikipedia
	 * @var string
	 */
	protected $fileFormat;
	
	/**
	 * @return string
	 */
	public function getHeadline(): string {
		return $this->headline;
	}
	
	/**
	 * @param string $headline
	 */
	public function setHeadline(string $headline): void {
		$this->headline = $headline;
	}
	
	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return string
	 */
	public function getLocale(): string {
		return $this->locale;
	}
	
	public function setLocale(string $locale): void {
		$this->locale = $locale;
	}
	
	/**
	 * @return string
	 */
	public function getAbout(): string {
		return $this->about;
	}
	
	/**
	 * @param string $about
	 */
	public function setAbout(string $about): void {
		$this->about = $about;
	}
	
	/**
	 * @return string
	 */
	public function getFileFormat(): string {
		return $this->fileFormat;
	}
	
	/**
	 * @param string $fileFormat
	 */
	public function setFileFormat(string $fileFormat): void {
		$this->fileFormat = $fileFormat;
	}
}