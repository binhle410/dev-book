<?php

namespace Bean\Bundle\PersonBundle\Doctrine\Orm;

use Bean\Component\Person\Model\Person as PersonModel;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

class Person extends PersonModel {
	/** @var UserInterface $user */
	protected $user;
	
	public function isSystemUserPersisted() {
		return ! ($this->user === null || empty($this->user->getId()));
//		return $this->user !== null;
	}
}
