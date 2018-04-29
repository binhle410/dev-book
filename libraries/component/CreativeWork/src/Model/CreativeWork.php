<?php

namespace Bean\Component\CreativeWork\Model;

class CreativeWork implements CreativeWorkInterface {
	protected $id;
	
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