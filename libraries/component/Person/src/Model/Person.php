<?php
declare(strict_types=1);

namespace Bean\Component\Person\Model;

use Bean\Component\Thing\Model\Thing;

class Person extends Thing implements PersonInterface
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @var \DateTime|null
     */
    protected $birthDate;

    /**
     * Given name. In the U.S., the first name of a Person. This can be used along with familyName instead of the name property.
     * @var string|null
     */
    protected $givenName;

    /**
     * Family name. In the U.S., the last name of an Person. This can be used along with givenName instead of the name property.
     * @var string|null
     */
    protected $familyName;

    /**
     * Gender of the person. While http://schema.org/Male and http://schema.org/Female may be used,
     * text strings are also acceptable for people who do not identify as a binary gender.
     * @var string|null
     */
    protected $gender;

    /**
     * Email address.
     * @var string|null
     */
    protected $email;

    /**
     * The telephone number.
     * @var string|null
     */
    protected $telephone;

    /**
     * @param null|string $familyName
     */
    public function setFamilyName(?string $familyName): void
    {
        $this->familyName = $familyName;
        $this->name = $this->givenName . ' ' . $familyName;
    }

    /**
     * @param null|string $givenName
     */
    public function setGivenName(?string $givenName): void
    {
        $this->givenName = $givenName;
        $this->name = $givenName . ' ' . $this->familyName;
    }

    /**
     * @return null|string
     */
    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    /**
     * @return null|string
     */
    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    /**
     * @return \DateTime|null
     */
    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime|null $birthDate
     */
    public function setBirthDate(?\DateTime $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return null|string
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param null|string $telephone
     */
    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return null|string
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param null|string $gender
     */
    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }
}
