<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\System;

interface FullTextSearchInterface {
	public function generateFullText();
	
	public function setFullText(?string $fullText): void;
	
	public function getFullText();
	
	public function generateSearchText();
	
	public function setSearchText(?string $searchText): void;
	
	public function getSearchText();
}
