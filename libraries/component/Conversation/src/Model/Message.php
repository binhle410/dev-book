<?php
declare(strict_types=1);

namespace Bean\Component\Conversation\Model;

use Bean\Component\CreativeWork\Model\CreativeWork;

class Message extends CreativeWork
{
    const STATUS_NEW = 'MESSAGE_NEW';
    const STATUS_RECEIVED = 'MESSAGE_RECEIVED';
    const STATUS_SEEN = 'MESSAGE_SEEN';

}
