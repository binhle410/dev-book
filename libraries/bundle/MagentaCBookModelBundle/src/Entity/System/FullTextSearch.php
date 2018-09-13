<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\System;

use Bean\Component\Organization\IoC\OrganizationAwareInterface;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;

/**
 * Class FullTextSearch
 * @package Magenta\Bundle\CBookModelBundle\Entity\System
 * @ORM\MappedSuperclass()
 */
abstract class FullTextSearch implements FullTextSearchInterface, OrganizationAwareInterface {
	/**
	 * @var string|null
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $searchText;
	
	/**
	 * @var string|null
	 * @ORM\Column(type="text", nullable=true)
	 */
	protected $fullText;
	
	public function setFullText(?string $text): void {
		$this->fullText = $text;
	}
	
	public function getFullText() {
		return $this->fullText;
	}
	
	/**
	 * @return null|string
	 */
	public function getSearchText(): ?string {
		return $this->searchText;
	}
	
	/**
	 * @param null|string $searchText
	 */
	public function setSearchText(?string $searchText): void {
		$this->searchText = $searchText;
	}
	
}
