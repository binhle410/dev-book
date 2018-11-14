<?php
declare(strict_types=1);

namespace Bean\Component\Messaging\IoC;

interface MessageDeliverableInterface
{
    /**
     * @return \Countable|\IteratorAggregate|\ArrayAccess|array|null
     */
    public function getMessageDeliveries();
}