<?php

namespace Magenta\Bundle\CBookModelBundle\Service;

class ServiceContext
{
    const TYPE_ADMIN_CLASS = 'ADMIN_CLASS';

    public function __construct($type = null, $attributes = null)
    {
        if (!empty($type) && !empty($attributes)) {
            $this->type = $type;
            $this->attributes = $attributes;
        }
    }

    protected $attributes = [];
    protected $type;

    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        {
            return null;
        }
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }
}
