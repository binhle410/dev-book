<?php
/**
 * Created by PhpStorm.
 * User: Binh
 * Date: 9/8/2018
 * Time: 8:04 PM
 */

namespace Bean\Component\CreativeWork\Model;

interface CreativeWorkInterface {
	/**
	 * @return string|null
	 */
	public function getHeadline(): ?string;
	
	/**
	 * @param string $headline
	 */
	public function setHeadline(string $headline): void;
	
	/**
	 * @return mixed
	 */
	public function getId();
	
	/**
	 * @return string
	 */
	public function getLocale(): string;
	
	/**
	 * @param string $locale
	 */
	public function setLocale(string $locale): void;
	
	/**
	 * @return string
	 */
	public function getAbout(): ?string;
	
	/**
	 * @param string $about
	 */
	public function setAbout(string $about): void;
	
	/**
	 * @return string
	 */
	public function getFileFormat(): ?string;
	
	/**
	 * @param string $fileFormat
	 */
	public function setFileFormat(string $fileFormat): void;
	
	/**
	 * @return \ArrayAccess|array|null
	 */
	public function getParts();
	
	/**
	 * @param array $parts
	 */
	public function setParts(array $parts): void;
	
	/**
	 * @return \Bean\Component\CreativeWork\Model\CreativeWorkInterface
	 */
	public function getPartOf(): ?CreativeWorkInterface;
	
	/**
	 * @param CreativeWorkInterface $partOf
	 */
	public function setPartOf(CreativeWorkInterface $partOf): void;
	
	/**
	 * @return null|string
	 */
	public function getText(): ?string;
	
	/**
	 * @param null|string $text
	 */
	public function setText(?string $text): void;
	
	/**
	 * @return int|null
	 */
	public function getVersionNumber(): ?int;
	
	/**
	 * @param int|null $versionNumber
	 */
	public function setVersionNumber(?int $versionNumber): void;
	
	/**
	 * @return int|null
	 */
	public function getPosition(): ?int;
	
	/**
	 * @param int|null $position
	 */
	public function setPosition(?int $position): void;
}
