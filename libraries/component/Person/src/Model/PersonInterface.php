<?php
declare(strict_types = 1);

namespace Bean\Component\Person\Model;

interface PersonInterface {
	/**
	 * @param null|string $familyName
	 */
	public function setFamilyName(?string $familyName): void;
	
	/**
	 * @param null|string $givenName
	 */
	public function setGivenName(?string $givenName): void;
	
	/**
	 * @return null|string
	 */
	public function getGivenName(): ?string;
	
	/**
	 * @return null|string
	 */
	public function getFamilyName(): ?string;
	
	/**
	 * @return \DateTime|null
	 */
	public function getBirthDate(): ?\DateTime;
	
	/**
	 * @param \DateTime|null $birthDate
	 */
	public function setBirthDate(?\DateTime $birthDate): void;


    /**
     * @return null|string
     */
    public function getEmail(): ?string;

    /**
     * @param null|string $email
     */
    public function setEmail(?string $email): void;

    /**
     * @return null|string
     */
    public function getTelephone(): ?string;

    /**
     * @param null|string $telephone
     */
    public function setTelephone(?string $telephone): void;

    /**
     * @return null|string
     */
    public function getGender(): ?string;

    /**
     * @param null|string $telephone
     */
    public function setGender(?string $gender): void;
}
