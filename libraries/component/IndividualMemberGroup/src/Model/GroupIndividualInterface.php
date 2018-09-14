<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\Model;

use Bean\Component\Organization\IoC\IndividualMemberAwareInterface;
use Bean\Component\Organization\IoC\IndividualMemberGroupAwareInterface;

interface GroupIndividualInterface extends IndividualMemberAwareInterface, IndividualMemberGroupAwareInterface {
	/**
	 * @param IndividualMemberInterface|null $individualMember
	 */
	public function setIndividualMember(?IndividualMemberInterface $individualMember): void;
	
	/**
	 * @param IndividualMemberGroupInterface|null $individualMemberGroup
	 */
	public function setIndividualMemberGroup(?IndividualMemberGroupInterface $individualMemberGroup): void;
}
