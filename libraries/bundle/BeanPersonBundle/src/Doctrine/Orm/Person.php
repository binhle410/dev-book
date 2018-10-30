<?php

namespace Bean\Bundle\PersonBundle\Doctrine\Orm;

use Bean\Component\Person\Model\Person as PersonModel;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;

class Person extends PersonModel
{
    /** @var UserInterface $user */
    protected $user;

    protected $persisted = false;

    public function isSystemUserPersisted()
    {
        return $this->persisted || !($this->user === null || empty($this->user->getId()));
//		return $this->user !== null;
    }

    /**
     * @param null|string $givenName
     */
    public function setGivenName(?string $givenName): void
    {
        parent::setGivenName($givenName);
        if (!empty($givenName)) {
            $this->name = trim($this->givenName . ' ' . $this->familyName);
        }
    }

    /**
     * @param null|string $familyName
     */
    public function setFamilyName(?string $familyName): void
    {
        parent::setFamilyName($familyName);
        if (!empty($familyName)) {
            $this->name = trim($this->givenName . ' ' . $this->familyName);
        }
    }

    /**
     * @return bool
     */
    public function isPersisted(): bool
    {
        return $this->persisted;
    }

    /**
     * @param bool $persisted
     */
    public function setPersisted(bool $persisted): void
    {
        $this->persisted = $persisted;
    }
}
