<?php
declare(strict_types=1);

namespace Bean\Component\CreativeWork\Model;

use Bean\Component\Thing\Model\ThingInterface;

interface CreativeWorkInterface extends ThingInterface {
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
	 * @return int
	 */
	public function getPosition(): int;
	
	/**
	 * @param int $position
	 */
	public function setPosition(int $position): void;
}