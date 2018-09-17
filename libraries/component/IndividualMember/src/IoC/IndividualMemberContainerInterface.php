<?php

namespace Bean\Component\Organization\IoC;

interface IndividualMemberContainerInterface {
	
	/**
	 * @return array|\ArrayAccess|\Countable|\IteratorAggregate|null
	 */
	public function getIndividualMembers();
	
	/**
	 * @param array|\ArrayAccess|\Countable|\IteratorAggregate|null $members
	 */
	public function setIndividualMembers($members): void;
}
