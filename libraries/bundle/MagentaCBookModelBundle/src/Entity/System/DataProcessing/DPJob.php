<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="system__data_processing__job")
 */
class DPJob
{
    const TYPE_MEMBER_IMPORT = 'MEMBER_IMPORT';

    const STATUS_PENDING = 'PENDING';
    const STATUS_LOCKED = 'LOCKED';
    const STATUS_SUCCESSFUL = 'SUCCESSFUL';
    const STATUS_FAILED = 'FAILED';

    /**
     * @var int|null
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        $this->logs = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * @return DPJob
     */
    public static function newInstance($resourceName, $type, $ownerId = null)
    {
        $obj = new DPJob();
        $obj->setResourceName($resourceName);
        if (!in_array($type, [self::TYPE_MEMBER_IMPORT])) {
            throw new \InvalidArgumentException();
        }
        $obj->setType($type);
        $obj->setOwnerId($ownerId);
        return $obj;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\System\DataProcessing\DPLog", mappedBy="job")
     */
    protected $logs;

    public function addLog(DPLog $log)
    {
        $this->logs->add($log);
        $log->setJob($this);
    }

    public function removeLog(DPLog $log)
    {
        $this->logs->removeElement($log);
        $log->setJob(null);
    }

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $startedAt;

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
     * @ORM\Column(type="integer", name="job_index", options={"default":0})
     */
    protected $index = 0;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $status = self::STATUS_PENDING;

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

    /**
     * @return Collection
     */
    public function getLogs(): Collection
    {
        return $this->logs;
    }

    /**
     * @param Collection $logs
     */
    public function setLogs(Collection $logs): void
    {
        $this->logs = $logs;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    /**
     * @param \DateTime $startedAt
     */
    public function setStartedAt(\DateTime $startedAt): void
    {
        $this->startedAt = $startedAt;
    }
}
