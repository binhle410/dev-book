<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Messaging;

use Bean\Component\Organization\Model\IndividualMember as MemberModel;

use Bean\Component\Thing\Model\Thing;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\AccessControl\ACRole;
use Magenta\Bundle\CBookModelBundle\Entity\Media\Media;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;
use Magenta\Bundle\CBookModelBundle\Entity\Person\Person;

/**
 * @ORM\Entity()
 * @ORM\Table(name="messaging__template")
 */
class MessageTemplate extends Thing {
	
	const TYPES = [
		'message_template.type_registration_verification' => self::TYPE_REGISTRATION_VERIFICATION,
		'message_template.type_registration_copy'         => self::TYPE_REGISTRATION_COPY
	];
	
	const TYPE_REGISTRATION_VERIFICATION = 'REGISTRATION_VERIFICATION'; // Registration
	const TYPE_REGISTRATION_COPY = 'REGISTRATION_COPY'; // Registration
	const TYPE_WARRANTY_APPROVED = 'REGISTRATION_APPROVED'; // Warranty
	const TYPE_WARRANTY_NEW_REGISTRATION = 'WARRANTY_NEW_REGISTRATION'; // Organisation
	const TYPE_TECHNICIAN_NEW_ASSIGNMENT = 'TECHNICIAN_NEW_ASSIGNMENT'; // OrganisationMember
	
	/**
	 * @var int|null
	 * @ORM\Id
	 * @ORM\Column(type="integer",options={"unsigned":true})
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @return int|null
	 */
	public function getId(): ?int {
		return $this->id;
	}
	
	/**
	 * @var Organisation|null
	 * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation", inversedBy="messageTemplates", cascade={"persist","merge"})
	 * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $organisation;
	
	/**
	 * @var string|null
	 * @ORM\Column(type="string",nullable=false)
	 */
	protected $type;
	
	/**
	 * @var string|null
	 * @ORM\Column(name="subject",type="string",nullable=true)
	 */
	protected $subject;
	
	/**
	 * @var string|null
	 * @ORM\Column(type="text",nullable=true)
	 */
	protected $content;
	
	/**
	 * @return null|string
	 */
	public function getSubject(): ?string {
		return $this->subject;
	}
	
	/**
	 * @param null|string $subject
	 */
	public function setSubject(?string $subject): void {
		$this->subject = $subject;
	}
	
	/**
	 * @return null|string
	 */
	public function getContent(): ?string {
		return $this->content;
	}
	
	/**
	 * @param null|string $content
	 */
	public function setContent(?string $content): void {
		$this->content = $content;
	}
	
	/**
	 * @return null|string
	 */
	public function getType(): ?string {
		return $this->type;
	}
	
	/**
	 * @param null|string $type
	 */
	public function setType(?string $type): void {
		$this->type = $type;
	}
}
