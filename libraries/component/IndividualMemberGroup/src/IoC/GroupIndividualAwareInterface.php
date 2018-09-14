<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\IoC;

interface GroupIndividualAwareInterface {
	/**
	 * @return \Countable|\IteratorAggregate|\ArrayAccess|array|null
	 */
	public function getGroupIndividuals();
	
	/**
	 * @param \Countable|\IteratorAggregate|\ArrayAccess|array|null $gi
	 *
	 * @return mixed
	 */
	public function setGroupIndividuals($gi);
}
