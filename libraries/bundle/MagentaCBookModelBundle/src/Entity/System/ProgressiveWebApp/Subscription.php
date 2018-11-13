<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\System\ProgressiveWebApp;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember;

/**
 * @ORM\Entity()
 * @ORM\Table(name="system__pwa__subscription")
 */
class Subscription
{
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
    public function getId(): ?int
    {
        return $this->id;
    }

    public static function createInstance($endpoint = null, $expirationTime = null, $p256dhKey = null, $authToken = null, $contentEncoding = 'aesgcm')
    {
        $instance = new Subscription();
        $instance->endpoint = $endpoint;
        $instance->expirationTime = $expirationTime;
        $instance->p256dhKey = $p256dhKey;
        $instance->authToken = $authToken;
        $instance->contentEncoding = $contentEncoding;
        return $instance;
    }

    /**
     * @var IndividualMember
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Organisation\IndividualMember", inversedBy="subscriptions")
     * @ORM\JoinColumn(name="id_individual_member", referencedColumnName="id")
     */
    protected $individualMember;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true, name="p256dh_key")
     */
    protected $p256dhKey;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true, name="auth_token")
     */
    protected $authToken;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true, name="endpoint")
     */
    protected $endpoint;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $contentEncoding;

    /**
     * @var double|null
     * @ORM\Column(type="bigint", nullable=true, name="expiration_time")
     */
    protected $expirationTime;

    /**
     * @return null|string
     */
    public function getP256dhKey(): ?string
    {
        return $this->p256dhKey;
    }

    /**
     * @param null|string $p256dhKey
     */
    public function setP256dhKey(?string $p256dhKey): void
    {
        $this->p256dhKey = $p256dhKey;
    }

    /**
     * @return null|string
     */
    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    /**
     * @param null|string $authToken
     */
    public function setAuthToken(?string $authToken): void
    {
        $this->authToken = $authToken;
    }

    /**
     * @return null|string
     */
    public function getEndpoint(): ?string
    {
        return $this->endpoint;
    }

    /**
     * @param null|string $endpoint
     */
    public function setEndpoint(?string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    /**
     * @return float|null
     */
    public function getExpirationTime(): ?float
    {
        return $this->expirationTime;
    }

    /**
     * @param float|null $expirationTime
     */
    public function setExpirationTime(?float $expirationTime): void
    {
        $this->expirationTime = $expirationTime;
    }

    /**
     * @return IndividualMember
     */
    public function getIndividualMember(): IndividualMember
    {
        return $this->individualMember;
    }

    /**
     * @param IndividualMember $individualMember
     */
    public function setIndividualMember(IndividualMember $individualMember): void
    {
        $this->individualMember = $individualMember;
    }

    /**
     * @return null|string
     */
    public function getContentEncoding(): ?string
    {
        return $this->contentEncoding;
    }

    /**
     * @param null|string $contentEncoding
     */
    public function setContentEncoding(?string $contentEncoding): void
    {
        $this->contentEncoding = $contentEncoding;
    }
}