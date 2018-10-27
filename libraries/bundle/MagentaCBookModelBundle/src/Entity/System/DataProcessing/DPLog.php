<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="system__data_processing__log")
 */
class DPLog
{
    const LEVEL_ERROR = 'ERROR';

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

    /**
     * @var DPJob
     * @ORM\ManyToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing\DPJob", inversedBy="logs")
     * @ORM\JoinColumn(name="id_job", referencedColumnName="id")
     */
    protected $job;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $index;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $value;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $level;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $message;

    /**
     * @return DPJob
     */
    public function getJob(): DPJob
    {
        return $this->job;
    }

    /**
     * @param DPJob $job
     */
    public function setJob(DPJob $job): void
    {
        $this->job = $job;
    }

    /**
     * @return int|null
     */
    public function getIndex(): ?int
    {
        return $this->index;
    }

    /**
     * @param int|null $index
     */
    public function setIndex(?int $index): void
    {
        $this->index = $index;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param null|string $value
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return null|string
     */
    public function getLevel(): ?string
    {
        return $this->level;
    }

    /**
     * @param null|string $level
     */
    public function setLevel(?string $level): void
    {
        $this->level = $level;
    }

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param null|string $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}