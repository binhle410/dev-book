<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\System;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="system__data_processing")
 */
class DataProcessing
{
    const TYPE_MEMBER_IMPORT = 'MEMBER_IMPORT';

    const STATUS_WORK_IN_PROGRESS = 'WORK_IN_PROGRESS';
    const STATUS_SUCCESSFUL = 'SUCCESSFUL';
    const STATUS_FAILED = 'FAILED';

    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $resourceName;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $type;

    /**
     * @var integer
     * @ORM\Column(type="integer", options={"default":0})
     */
    protected $index = 0;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $status = self::STATUS_WORK_IN_PROGRESS;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ownerId;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return null|string
     */
    public function getOwnerId(): ?string
    {
        return $this->ownerId;
    }

    /**
     * @param null|string $ownerId
     */
    public function setOwnerId(?string $ownerId): void
    {
        $this->ownerId = $ownerId;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @param int $index
     */
    public function setIndex(int $index): void
    {
        $this->index = $index;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return null|string
     */
    public function getResourceName(): ?string
    {
        return $this->resourceName;
    }

    /**
     * @param null|string $resourceName
     */
    public function setResourceName(?string $resourceName): void
    {
        $this->resourceName = $resourceName;
    }
}
