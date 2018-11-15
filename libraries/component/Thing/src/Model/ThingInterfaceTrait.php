<?php

namespace Bean\Component\Thing\Model;

trait ThingInterfaceTrait
{
    
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;
            $objProps = $this->getObjectProperties();
            foreach ($objProps as $prop) {
                $this->{$prop} = clone $this->{$prop};
            }
            $objArrayProps = $this->getObjectArrayProperties();
            foreach ($objArrayProps as $prop => $inversedMethod) {
                $cloned = [];
                foreach ($this->{$prop} as $item) {
                    $clonedItem = clone $item;
                    $clonedItem->{$inversedMethod}($this);
                    $cloned[] = $clonedItem;
                }
                $this->{$prop} = $cloned;
            }
        }
    }
    
    public function copyScalarPropertiesFrom(ThingInterface $thing)
    {
        $vars = get_object_vars($this);
        foreach ($vars as $prop => $value) {
            $setter = 'set' . ucfirst($prop);
            $getter = 'get' . ucfirst($prop);
            if (empty($value) && method_exists($thing, $setter)) {
                if (!method_exists($thing, $getter)) {
                    $getter = 'is' . ucfirst($prop);
                }
                if (method_exists($thing, $setter)) {
                    $getterValue = $thing->$getter();
                    if (is_scalar($getterValue)) {
                        $this->$setter($getterValue);
                    }
                }
            }
        }
    }
    
    protected function getObjectArrayProperties()
    {
        return [];
    }
    
    protected function getObjectProperties()
    {
        return [];
    }
    
    /**
     * NOT part of schema.org
     *
     * @param $element
     * @param $prop
     *
     * @return bool
     */
    protected function addElementToArrayProperty($element, $prop)
    {
        $this->{$prop}[] = $element;
        
        return true;
        
    }
    
    /**
     * NOT part of schema.org
     *
     * @param $el
     * @param $array
     *
     * @return bool
     */
    protected function removeElementFromArrayProperty($element, $prop)
    {
        $key = array_search($element, $this->{$prop}, true);
        if ($key === false) {
            return false;
        }
        unset($this->{$prop}[$key]);
        
        return true;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
    
    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
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
    public function setCreatedAt(?\DateTime $createdAt = null): void
    {
        $this->createdAt = $createdAt;
    }
    
    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
    
    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(?\DateTime $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt;
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
    public function setName($name): void
    {
        $this->name = $name;
    }
    
    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * @param null|string $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }
    
    /**
     * @return null|string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }
    
    /**
     * @param null|string $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
    
    
    /**
     * @return bool|null
     */
    public function isLocked(): bool
    {
        return !empty($this->locked);
    }
    
    /**
     * @return bool|null
     */
    public function getLocked(): ?bool
    {
        return $this->locked;
    }
    
    /**
     * @param bool|null $locked
     */
    public function setLocked(?bool $locked): void
    {
        $this->locked = $locked;
    }
    
}