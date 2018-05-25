<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Thing\Model\Thing;

class Organization extends Thing {
	/**
	 * NOT part of schema.org
	 * @var \Countable|\IteratorAggregate|\ArrayAccess|array|null
	 */
	protected $members;
}