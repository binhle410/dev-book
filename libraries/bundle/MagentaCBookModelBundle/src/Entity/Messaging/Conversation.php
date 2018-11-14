<?php

namespace Magenta\Bundle\CBookModelBundle\Entity\Messaging;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Organisation\Organisation;

/**
 * @ORM\Entity()
 * @ORM\Table(name="messaging__conversation")
 */
class Conversation extends \Bean\Component\Messaging\Model\Conversation
{
    /**
     * @ORM\OneToMany(
     *     targetEntity="Magenta\Bundle\CBookModelBundle\Entity\Messaging\Message",
     *     mappedBy="conversation", cascade={"persist"}
     * )
     *
     * @var \Doctrine\Common\Collections\Collection $messages
     */
    protected $messages;
}