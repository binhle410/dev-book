<?php
declare(strict_types = 1);

namespace Bean\Component\Organization\IoC;

use Bean\Component\Organization\Model\IndividualMemberGroupInterface;

/**
 * Interface IndividualMemberGroupAwareInterface
 * This is not part of schema.org
 * @package Bean\Component\Organization
 */
interface IndividualMemberGroupAwareInterface {
	/**
	 * @return IndividualMemberGroupInterface|null
	 */
	public function getIndividualMemberGroup();
}
