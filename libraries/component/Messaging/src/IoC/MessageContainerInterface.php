<?php
declare(strict_types=1);

namespace Bean\Component\Messaging\IoC;

interface MessageContainerInterface
{
    /**
     * @return \Countable|\IteratorAggregate|\ArrayAccess|array|null
     */
    public function getMessages();
}