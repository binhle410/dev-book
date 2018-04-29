<?php

namespace Bean\Component\CreativeWork\Model;

use Bean\Component\Thing\Model\Thing;

class CreativeWork extends Thing implements CreativeWorkInterface {
	protected $headline;
	
	/**
	 * @return string
	 */
	public function getHeadline() {
		return $this->headline;
	}
	
	/**
	 * @param string $headline
	 */
	public function setHeadline($headline): void {
		$this->headline = $headline;
	}
	
	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
}