<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\IoC;

use Bean\Component\Organization\Model\IndividualMemberInterface;

/**
 * Interface IndividualMemberAwareInterface
 * This is not part of schema.org
 * @package Bean\Component\Organization
 */
interface IndividualMemberAwareInterface {
	/**
	 * @return IndividualMemberInterface|null
	 */
	public function getIndividualMember();
}
