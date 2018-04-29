<?php
declare(strict_types=1);

namespace Bean\Component\CreativeWork\Model;

use Bean\Component\Thing\Model\ThingInterface;

interface CreativeWorkInterface extends ThingInterface{
	
	/**
	 * @return string
	 */
	public function getHeadline(): string;
	
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
	public function getAbout(): string;
	
	/**
	 * @param string $about
	 */
	public function setAbout(string $about): void;
}