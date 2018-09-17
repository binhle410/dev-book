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
}
