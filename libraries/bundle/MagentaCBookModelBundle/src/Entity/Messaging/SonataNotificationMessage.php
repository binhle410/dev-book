<?php

declare(strict_types=1);

namespace Magenta\Bundle\CBookModelBundle\Entity\Messaging;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sonata\NotificationBundle\Entity\BaseMessage;

/**
 * @ORM\Entity
 * @ORM\Table(name="notification__message")
 */
class SonataNotificationMessage extends BaseMessage
{
    const TYPE_MEMBER_IMPORT = 'member-import';
    const TYPE_PWA_PUSH_NOTIFICATION = 'pwa-push-notification';
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * // Serializer\Groups(groups={"sonata_api_read","sonata_api_write","sonata_search"})
     *
     * @var int
     */
    protected $id;
    
    public function getId()
    {
        return $this->id;
    }
}
